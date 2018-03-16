<?php

namespace storage\models;

use app\components\behaviors\PrimaryKeyBehavior;
use creocoder\nestedsets\NestedSetsBehavior;
use storage\behaviors\StorageTreeBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class StorageTree stores Storage elements structure as multidimensional tree.
 * It uses Nested Sets model for representing trees in relational databases.
 *
 * @property int $id
 * @property string $tree_uuid
 * @property string $storage_uuid
 * @property int $root
 * @property int $left
 * @property int $right
 * @property int $level
 *
 * @property Storage $storage
 *
 * @method StorageTreeQuery parents($depth = null)
 * @method StorageTreeQuery children($depth = null)
 * @method StorageTreeQuery appendTo($node)
 * @method StorageTreeQuery makeRoot()
 * @method StorageTreeQuery isRoot()
 * @method StorageTreeQuery deleteWithChildren()
 *
 * @package storage\models
 * @see https://github.com/creocoder/yii2-nested-sets
 */
class StorageTree extends ActiveRecord
{
    /**
     * @return StorageTreeQuery
     */
    public static function find()
    {
        return new StorageTreeQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'storage.title' => 'File/folder',
            'storage.file.size' => 'Size',
            'storage.workflow.created.fullname' => 'Owner',
            'storage.workflow.modified_date' => 'Uploaded'
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
     * Returns a list of behaviors that this component should behave as.
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = ['class' => PrimaryKeyBehavior::class, 'attribute' => 'tree_uuid'];
        $behaviors['storage'] = ['class' => StorageTreeBehavior::class];
        $behaviors['tree'] = [
            'class' => NestedSetsBehavior::class,
            'leftAttribute' => 'left',
            'rightAttribute' => 'right',
            'treeAttribute' => 'root',
            'depthAttribute' => 'level'
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => [self::OP_DELETE]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorage()
    {
        return $this->hasOne(Storage::class, ['uuid' => 'storage_uuid']);
    }

    /**
     * @param StorageSettings $settings
     * @return ActiveDataProvider
     */
    public static function search(StorageSettings $settings = null)
    {
        $defaultOrder = ['storage.title' => SORT_ASC];
        if ($settings) {
            $defaultOrder = [$settings->sortBy => $settings->sortOrder];
        }

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => $defaultOrder,
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes()
    {
        return [
            'storage.title' => [
                'asc' => ['{{%storage}}.[[title]]' => SORT_ASC],
                'desc' => ['{{%storage}}.[[title]]' => SORT_DESC],
            ],
            'storage.file.size' => [
                'asc' => ['{{%storage_files}}.[[size]]' => SORT_ASC],
                'desc' => ['{{%storage_files}}.[[size]]' => SORT_DESC],
            ],
            'storage.workflow.modified_date' => [
                'asc' => ['{{%workflow}}.[[modified_date]]' => SORT_ASC],
                'desc' => ['{{%workflow}}.[[modified_date]]' => SORT_DESC]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getCanonicalPath()
    {
        $path = [];

        /* @var StorageTree[] $parents */
        $parents = $this->parents()->type(Storage::STORAGE_TYPE_DIR)->all();

        foreach ($parents as $parent) {
            $path[$parent->tree_uuid] = $parent->storage->title;
        }

        return $path;
    }

    /**
     * @param array $params
     * @return array
     */
    public static function getPath($params = ['index'])
    {
        $path = [/*[\Yii::t('storage', 'Root'), $params]*/];
        $tree_uuid = \Yii::$app->request->get('tree_uuid');

        /* @var $tree StorageTree */
        $tree = self::find()->where(['tree_uuid' => $tree_uuid])->one();

        if ($tree) {
            foreach ($tree->getCanonicalPath() as $id => $title) {
                $params['tree_uuid'] = $id;
                $path[] = [$title, $params];
            }

            $path[] = [$tree->storage->title];
        }

        return $path;
    }

    /**
     * @return StorageTreeQuery
     */
    protected static function prepareSearchQuery()
    {
        $tree_uuid = \Yii::$app->request->get('tree_uuid');

        /* @var StorageTreeQuery $root */
        $root = self::find()->where(['tree_uuid' => $tree_uuid])->one();

        if ($root) {
            $query = $root->children(1);
        } else {
            $query = self::find()->roots();
        }

        return $query
            ->joinWith(['storage', 'storage.workflow', 'storage.file'])
            ->orderBy(['{{%storage}}.[[type]]' => SORT_ASC]);
    }
}
