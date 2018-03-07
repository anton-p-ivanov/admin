<?php

namespace catalogs\models;

use app\models\Workflow;
use i18n\components\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Type
 * @property string $uuid
 * @property string $title
 * @property string $code
 * @property integer $sort
 * @property string $workflow_uuid
 *
 * @package catalogs\models
 */
class Type extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_types}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('catalogs/types', $message, $params);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ml'] = ArrayHelper::merge($behaviors['ml'], [
            'langForeignKey' => 'type_uuid',
            'tableName' => '{{%catalogs_types_i18n}}',
            'attributes' => ['title']
        ]);

        return $behaviors;
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
    public static function getList()
    {
        return self::find()
            ->joinWith('translation')
            ->select('title')
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->indexBy('uuid')
            ->column();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }
}
