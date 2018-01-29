<?php

namespace catalogs\modules\admin\modules\fields\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\components\traits\ActiveSearch;
use app\models\Workflow;
use catalogs\modules\admin\models\Catalog;
use yii\db\ActiveRecord;

/**
 * Class Group
 *
 * @property string $uuid
 * @property string $title
 * @property boolean $active
 * @property integer $sort
 * @property string $catalog_uuid
 * @property string $workflow_uuid
 *
 * @property Workflow $workflow
 *
 * @package catalogs\modules\admin\modules\fields\models
 */
class Group extends ActiveRecord
{
    use ActiveSearch;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_fields_groups}}';
    }

    /**
     * @param string $catalog_uuid
     * @return array
     */
    public static function getList($catalog_uuid)
    {
        return self::find()
            ->where(['catalog_uuid' => $catalog_uuid])
            ->select('title')
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->indexBy('uuid')
            ->column();
    }


    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('catalogs/groups', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'active' => 'Active',
            'sort' => 'Sort',
            'title' => 'Title',
            'catalog_uuid' => 'Catalog',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'active' => 'Whether group is active.',
            'sort' => 'Sorting index. Default is 100.',
            'title' => 'Up to 255 characters length.',
            'catalog_uuid' => 'Select one of available catalogs.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = WorkflowBehavior::className();
        $behaviors[] = PrimaryKeyBehavior::className();

        return $behaviors;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'catalog_uuid'], 'required', 'message' => self::t('{attribute} is required.')],
            ['title', 'string', 'max' => 255, 'tooLong' => 'Maximum {max, number} characters allowed.'],
            ['active', 'boolean'],
            ['sort', 'integer', 'min' => 0, 'tooSmall' => '{attribute} value must be greater or equal {min, number}.'],
            ['catalog_uuid', 'exist', 'targetClass' => Catalog::className(), 'targetAttribute' => 'uuid'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::className(), ['uuid' => 'workflow_uuid']);
    }
}