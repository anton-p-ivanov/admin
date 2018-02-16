<?php

namespace catalogs\models;

use app\components\behaviors\PrimaryKeyBehavior;
use catalogs\behaviors\ElementTreeBehavior;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class ElementTree
 *
 * Stores catalog`s elements structure as multidimensional tree.
 * It uses Nested Sets model for representing trees in relational databases.
 *
 * @property int $id
 * @property string $tree_uuid
 * @property string $element_uuid
 * @property int $root
 * @property int $left
 * @property int $right
 * @property int $level
 *
 * @property Element $element
 *
 * @method ElementTreeQuery parents($depth = null)
 * @method ElementTreeQuery children($depth = null)
 * @method ElementTreeQuery appendTo($node)
 * @method ElementTreeQuery makeRoot()
 * @method ElementTreeQuery isRoot()
 *
 * @package catalogs\models
 */
class ElementTree extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_elements_tree}}';
    }

    /**
     * @return ElementTreeQuery
     */
    public static function find()
    {
        return new ElementTreeQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [

        ];

        return array_map('self::t', $labels);
    }

    /**
     * @param string $label
     * @return string
     */
    public static function t($label)
    {
        return \Yii::t('catalogs/elements', $label);
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = ['class' => PrimaryKeyBehavior::className(), 'attribute' => 'tree_uuid'];
        $behaviors['element'] = ['class' => ElementTreeBehavior::className()];
        $behaviors['tree'] = [
            'class' => NestedSetsBehavior::className(),
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
    public function getElement()
    {
        return $this->hasOne(Element::className(), ['uuid' => 'element_uuid']);
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params = [])
    {
        $defaultOrder = ['element.title' => SORT_ASC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($params),
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
            'element.title' => [
                'asc' => ['{{%catalogs_elements}}.[[title]]' => SORT_ASC],
                'desc' => ['{{%catalogs_elements}}.[[title]]' => SORT_DESC],
            ],
            'element.workflow.modified_date' => [
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

        /* @var ElementTree[] $parents */
        $parents = $this->parents()->type(Element::ELEMENT_TYPE_SECTION)->all();

        foreach ($parents as $parent) {
            $path[$parent->tree_uuid] = $parent->element->title;
        }

        return $path;
    }

    /**
     * @param array $params
     * @return array
     */
    public static function getPath($params = ['index'])
    {
        $path = [];
        $tree_uuid = \Yii::$app->request->get('tree_uuid');

        /* @var $tree ElementTree */
        $tree = self::find()->where(['tree_uuid' => $tree_uuid])->one();

        if ($tree) {
            foreach ($tree->getCanonicalPath() as $id => $title) {
                $params['tree_uuid'] = $id;
                $path[] = [$title, $params];
            }

            $path[] = [$tree->element->title];
        }

        return $path;
    }

    /**
     * @param array $params
     * @return ElementTreeQuery
     */
    protected static function prepareSearchQuery($params = [])
    {
        /* @var ElementTreeQuery $root */
        $root = self::find()->where($params)->one();

        if ($root) {
            $query = $root->children(1);
        } else {
            $query = self::find()->roots();
        }

        return $query
            ->joinWith(['element', 'element.workflow'])
            ->orderBy(['{{%catalogs_elements}}.[[type]]' => SORT_DESC]);
    }
}
