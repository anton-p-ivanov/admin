<?php

namespace catalogs\models;

use app\models\Workflow;
use i18n\components\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Catalog
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 * @property string $code
 * @property integer $sort
 * @property boolean $active
 * @property boolean $trade
 * @property boolean $index
 * @property string $type_uuid
 * @property string $tree_uuid
 * @property string $workflow_uuid
 *
 * @property Workflow $workflow
 * @property Type $type
 *
 * @package catalogs\models
 */
class Catalog extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('catalogs/catalogs', $message, $params);
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
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ml'] = ArrayHelper::merge($behaviors['ml'], [
            'langForeignKey' => 'catalog_uuid',
            'tableName' => '{{%catalogs_i18n}}',
            'attributes' => ['title', 'description']
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
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::class, ['uuid' => 'type_uuid']);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (int) $this->active === 1;
    }

    /**
     * @return bool
     */
    public function isTrading()
    {
        return (int) $this->trade === 1;
    }
}
