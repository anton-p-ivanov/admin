<?php
namespace app\models;

use app\components\behaviors\PrimaryKeyBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\QueryInterface;
use yii\helpers\Json;

/**
 * Class Filter
 * @property string $uuid
 * @property string $query
 * @property string $hash
 *
 * @package app\models
 */
abstract class Filter extends ActiveRecord
{
    /**
     * @var bool
     */
    public $isActive = false;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%filters}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::className();
        $behaviors['ts'] = [
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => false,
            'value' => new Expression('NOW()')
        ];

        return $behaviors;
    }

    /**
     * @param QueryInterface|ActiveQuery $query
     */
    abstract public function buildQuery(&$query);

    /**
     * @param string $filter_uuid
     * @return Filter
     */
    public static function loadFilter($filter_uuid)
    {
        $className = self::className();

        /* @var Filter $model */
        $model = self::findOne($filter_uuid) ?: new $className();
        $model->load(Json::decode($model->query), '');

        return $model;
    }
}
