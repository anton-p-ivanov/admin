<?php
namespace storage\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\PurifyBehavior;
use app\models\Workflow;
use storage\helpers\FileHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\web\HttpException;

/**
 * Class StorageFile
 *
 * @property string $uuid
 * @property string $name
 * @property integer $size
 * @property string $type
 * @property string $hash
 *
 * @property StorageImage $image
 * @property Storage $storage
 *
 * @package storage\models
 */
class StorageFile extends ActiveRecord
{
    /**
     * Constants
     */
    const SCENARIO_RENAME = 'rename';
    /**
     * @var string
     */
    public $storage_uuid;
    /**
     * @var boolean Transliterate file name
     */
    public $useTranslit = true;
    /**
     * @var boolean Replace spaces with underscores in file name
     */
    public $useUnderscore = true;
    /**
     * @var array Workflow items to be removed
     */
    private $_workflow = [];

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%storage_files}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::class;
        $behaviors['purify'] = [
            'class' => PurifyBehavior::class,
            'attributes' => ['name', 'type'],
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'name' => 'Type file name',
            'useTranslit' => 'Transliterate non-latin characters',
            'useUnderscore' => 'Replace all spaces with underscore'
        ];

        return array_map('self::t', $labels);
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
        switch ($this->scenario) {
            case self::SCENARIO_RENAME:
                $rules = [
                    ['name', 'required'],
                    ['name', 'string', 'max' => 255],
                    [['useTranslit', 'useUnderscore'], 'boolean']
                ];
                break;
            default:
                $rules = [
                    [['name', 'size', 'type'], 'required'],
                    [['name', 'type'], 'string', 'max' => 255],
                    ['size', 'integer', 'min' => 0],
                    ['hash', 'string', 'max' => 32],
                    ['name', 'unique', 'targetAttribute' => ['name', 'size'],
                        'message' => \Yii::t('storage', 'File `{value}` is already exists.')],
                ];
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_RENAME] = ['name', 'useTranslit', 'useUnderscore'];

        return $scenarios;
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if ($result = parent::beforeValidate()) {
            if ($this->useUnderscore) {
                $this->name = preg_replace('/\s/', '_', $this->name);
            }

            if ($this->useTranslit) {
                $this->name = Inflector::transliterate($this->name);
            }
        }

        return $result;
    }

    /**
     * @return bool
     * @throws HttpException
     */
    public function beforeDelete(): bool
    {
        $result = parent::beforeDelete();

        if ($result) {
            $version = StorageVersion::findOne(['file_uuid' => $this->uuid]);
            if ($version) {
                if ($version->isActive()) {
                    throw new HttpException(400, 'Only inactive versions could be deleted.');
                }

                $this->_workflow = [$version->workflow_uuid];
            }
        }

        return $result;
    }

    /**
     * This method is invoked after deleting a record.
     */
    public function afterDelete()
    {
        // Calling parent method implementation
        parent::afterDelete();

        // Deleting linked Workflow records
        if ($this->_workflow) {
            $items = Workflow::findAll($this->_workflow);
            foreach ($items as $item) {
                $item->delete();
            }
        }

        // Deleting file from filesystem
        FileHelper::removeFile([$this->uuid]);
    }

    /**
     * Checks whether a file is an image
     * @return bool
     */
    public function isImage()
    {
        return strpos($this->type, 'image/') !== false;
    }

    /**
     * @param array $data
     * @return StorageFile
     */
    public static function normalizeFiles(array $data)
    {
        $normalizedArray = [
            'name' => null,
            'size' => 0,
            'type' => 'application/octet-stream',
            'hash' => null
        ];

        $data = array_merge($normalizedArray, array_intersect_key($data, $normalizedArray));

        $file = new StorageFile();
        $file->setAttributes($data);

        return $file;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->insertVersion();
        }

        if ($this->isImage()) {
            $this->updateImageData();
        }
    }

    /**
     * @return bool
     */
    protected function updateImageData(): bool
    {
        $image = $this->image ?: new StorageImage();
        $image->setAttributes([
            'file_uuid' => $this->uuid,
            'width' => 0,
            'height' => 0
        ]);

        return $image->save();
    }

    /**
     * @return ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(StorageImage::class, ['file_uuid' => 'uuid']);
    }

    /**
     * @return bool
     */
    protected function insertVersion(): bool
    {
        return (new StorageVersion([
            'storage_uuid' => $this->storage_uuid,
            'file_uuid' => $this->uuid,
            'active' => true,
        ]))->save();
    }

    /**
     * @return ActiveQuery
     */
    public function getStorage()
    {
        return $this->hasOne(Storage::class, ['uuid' => 'storage_uuid'])
            ->viaTable(StorageVersion::tableName(), ['file_uuid' => 'uuid']);
    }
}
