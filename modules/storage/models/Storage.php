<?php
namespace storage\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Workflow;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Class Storage
 * @property string $uuid
 * @property string $type
 * @property string $title
 * @property string $description
 * @property string $workflow_uuid
 *
 * @property StorageFile $file
 * @property StorageVersion[] $versions
 * @property StorageTree[] $tree
 * @property Workflow $workflow
 * @property array $locations
 *
 * @package storage\models
 */
class Storage extends ActiveRecord
{
    /**
     * Storage type file
     */
    const STORAGE_TYPE_FILE = 'F';
    /**
     * Storage type folder/directory
     */
    const STORAGE_TYPE_DIR = 'D';
    /**
     * @var array
     */
    public static $types = [self::STORAGE_TYPE_DIR, self::STORAGE_TYPE_FILE];
    /**
     * @var StorageFile[]
     */
    public $files;
    /**
     * @var array
     */
    private $_locations;
    /**
     * @var \yii\db\Transaction
     */
    private $_transaction;

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'title' => $this->isDirectory() ? 'Title' : 'File name',
            'locations' => 'Locations',
            'description' => 'Description',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'title' => $this->isDirectory()
                ? 'Folder name up to 200 chars length.'
                : 'This name will be used when downloading the file.',
            'description' => 'Describe file or folder contents. 500 chars max.',
            'locations' => 'Select one of available folders.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @param string $label
     * @return string
     */
    public static function t($label)
    {
        return \Yii::t('storage', $label);
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = [
            ['locations', 'validateLocations', 'when' => [$this, 'hasLocations']],
            [['title', 'type'], 'required', 'message' => \Yii::t('storage', 'Field\'s value must not be empty.')],
            ['type', 'in', 'range' => self::$types],
            ['title', 'string', 'max' => 200],
            ['description', 'string', 'max' => 500],
            ['description', 'default', 'value' => ''],
        ];

        if (!$this->isDirectory()) {
            $rules[] = ['files', 'validateUpload'];
        }

        return $rules;
    }

    /**
     * @return bool
     */
    public function hasLocations(): bool
    {
        return is_array($this->_locations) && count($this->_locations) > 0;
    }

    /**
     * @return bool
     */
    public function isDirectory(): bool
    {
        return $this->type === self::STORAGE_TYPE_DIR;
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::class;
        $behaviors['workflow'] = WorkflowBehavior::class;

        return $behaviors;
    }

    /**
     * @return bool
     */
    public function beforeValidate(): bool
    {
        if ($result = parent::beforeValidate()) {
            // Filter user selected locations
            if ($this->_locations) {
                $this->filterLocations();
            }

            // Prepare uploaded files
            if ($this->files) {
                $this->files = array_map([StorageFile::class, 'normalizeFiles'], Json::decode($this->files));
            }
        }

        return $result;
    }

    /**
     * @return bool
     * @throws HttpException
     */
    public function beforeDelete()
    {
        // To avoid nested tree collisions `Storage` model could not be deleted directly.
        // Use `StorageTree::delete()` method.
        throw new HttpException(400, \Yii::t('storage', 'Could not perform `delete` action to `Storage` model'));
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);

        if ($result) {
            $this->_transaction = $this->getDb()->beginTransaction();
        }

        return $result;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        // Calling method parent implementation
        parent::afterSave($insert, $changedAttributes);

        $result = true;

        if ($this->files) {
            $result = $this->insertFiles() && $result;
        }

        if ($result) {
            ($insert ? $this->insertNodes() : $this->updateNodes());
        }

        $methodName = $result ? 'commit' : 'rollBack';
        $this->_transaction->$methodName();
    }

    /**
     * @param $attribute
     */
    public function validateUpload($attribute)
    {
        $models = [];

        foreach ($this->$attribute as $i => $file) {
            if ($model = $this->validateFile($attribute, $file)) {
                $models[] = $model;
            }
        }

        $this->$attribute = $models;
    }

    /**
     * @param string $attribute
     * @param StorageFile $model
     * @return StorageFile|bool
     */
    protected function validateFile($attribute, StorageFile $model)
    {
        if ($model->validate()) {
            return $model;
        }
        else {
            foreach ($model->errors as $errors) {
                foreach ($errors as $error) {
                    $this->addError($attribute, $error);
                }
            }
        }

        return false;
    }

    /**
     * @param $attribute
     */
    public function validateLocations($attribute)
    {
        $query = StorageTree::find()
            ->type(self::STORAGE_TYPE_DIR)
            ->andWhere(['{{%storage_tree}}.[[tree_uuid]]' => $this->$attribute]);

        if (count($this->$attribute) != $query->count()) {
            $this->addError($attribute, \Yii::t('storage', 'One or more selected locations are not a directory.'));
        }
    }

    /**
     * Filter user selected locations
     */
    protected function filterLocations()
    {
        if (!is_array($this->_locations)) {
            $this->_locations = [$this->_locations];
        }

        // Filtering empty values
        $this->_locations = array_filter($this->_locations, function ($value) {
            return !empty($value);
        });

        // Filtering non-unique values
        $this->_locations = array_unique($this->_locations);

        // If a tree node is a `directory` only one location is allowed
        if ($this->isDirectory() && $this->_locations) {
            $this->_locations = [array_shift($this->_locations)];
        }
    }

    /**
     * @return integer Number of files successfully saved.
     */
    protected function insertFiles()
    {
        $i = 0;

        foreach ($this->files as $file) {
            $file->storage_uuid = $this->uuid;
            $i += $file->save();
        }

        return $i;
    }

    /**
     * Insert new node into storage tree
     */
    protected function insertNodes()
    {
        if ($this->_locations) {
            $nodes = StorageTree::find()
                ->type(self::STORAGE_TYPE_DIR)
                ->where(['{{%storage_tree}}.[[tree_uuid]]' => $this->_locations])
                ->all();

            foreach ($nodes as $node) {
                $tree = new StorageTree();
                $tree->storage_uuid = $this->uuid;
                $tree->appendTo($node);
            }
        }
        else {
            $tree = new StorageTree();
            $tree->storage_uuid = $this->uuid;
            $tree->makeRoot();
        }
    }

    /**
     * Update node in storage tree
     */
    protected function updateNodes()
    {
        $tree = $this->tree;
        if ($this->isDirectory()) {
            $node = array_shift($tree);

            if (!$this->_locations) {
                if (!$node->isRoot()) {
                    $node->makeRoot();
                }
            }
            else {
                $parent = ($parent = $node->parents(1)->one()) ?: new StorageTree();

                if ($parent && [$parent->id] !== $this->_locations) {
                    $root = StorageTree::find()
                        ->type(self::STORAGE_TYPE_DIR)
                        ->where(['{{%storage_tree}}.[[tree_uuid]]' => $this->_locations])
                        ->one();

                    if ($root) {
                        $node->appendTo($root);
                    }
                }
            }
        }
        else {
            // Delete old tree nodes
            foreach ($tree as $node) {
                $node->detachBehavior('storage');
                $node->delete();
            }

            // Insert new tree nodes
            $this->insertNodes();
        }
    }

    /**
     * @return array
     */
    public function getLocations()
    {
        if ($this->_locations === null && $this->tree) {
            foreach ($this->tree as $node) {
                if ($parent = $node->parents(1)->one()) {
                    $this->_locations[] = $parent->tree_uuid;
                }
            }
        }

        return $this->_locations;
    }

    /**
     * @param array $locations
     */
    public function setLocations($locations)
    {
        $this->_locations = $locations;
    }

    /**
     * @return ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(StorageFile::class, ['uuid' => 'file_uuid'])
            ->viaTable(StorageVersion::tableName(), ['storage_uuid' => 'uuid'], function (ActiveQuery $query) {
                $query->where(['or',
                    StorageVersion::tableName() . '.[[active]] = :active',
                    StorageVersion::tableName() . '.[[active]] IS NULL'
                ])->params([':active' => true]);
            });
    }

    /**
     * @return ActiveQuery
     */
    public function getTree()
    {
        return $this->hasMany(StorageTree::class, ['storage_uuid' => 'uuid']);
    }

    /**
     * @return ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVersions()
    {
        return $this->hasMany(StorageVersion::class, ['storage_uuid' => 'uuid'])
            ->joinWith(['file', 'workflow'])
            ->orderBy(['{{%workflow}}.[[created_date]]' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(StorageRole::class, ['storage_uuid' => 'uuid']);
    }
}
