<?php
namespace users\modules\fields\models;

use app\models\Filter;
use app\models\User;
use fields\models\Field;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class FieldFilter
 *
 * @package users\modules\fields\models
 */
class FieldFilter extends Filter
{
    /**
     * @var string
     */
    public $owner;
    /**
     * @var integer
     */
    public $type;
    /**
     * @var boolean
     */
    public $multiple;
    /**
     * @var boolean
     */
    public $active;
    /**
     * @var boolean
     */
    public $list;

    /**
     * @param \yii\db\ActiveQuery $query
     */
    public function buildQuery(&$query)
    {
        try {
            $attributes = array_filter(Json::decode($this->query), function ($attribute) {
                return !empty($attribute);
            });

            $this->isActive = true;
        }
        catch (\Exception $exception) {
            $attributes = [];
        }

        foreach ($attributes as $attribute => $value) {
            switch ($attribute) {
                case 'owner':
                    $query->andFilterWhere(['{{%workflow}}.[[created_by]]' => $value]);
                    break;
                case 'multiple':
                case 'active':
                case 'type':
                case 'list':
                    $query->andFilterWhere(["{{%users_fields}}.[[$attribute]]" => $value]);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'owner' => 'Owner',
            'type' => 'Type',
            'multiple' => 'Multiple',
            'active' => 'Active',
            'list' => 'List'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'owner' => 'User who created the field.',
            'type' => 'Type of the element.',
            'active' => 'Element`s activity.',
            'multiple' => 'Can accept multiple values.',
            'list' => 'Can be displayed in grids and lists.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['owner', 'in', 'range' => array_keys(self::getOwners()), 'message' => self::t('Invalid owner.')],
            [['active', 'multiple', 'list'], 'boolean', 'message' => self::t('Invalid {attribute} value selected.')],
            ['type', 'in', 'range' => array_keys(self::getTypes()), 'message' => self::t('Invalid type.')]
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            $this->query = Json::encode([
                'class' => md5(self::className()),
                'owner' => $this->owner,
                'type' => $this->type,
                'active' => $this->active,
                'multiple' => $this->multiple,
                'list' => $this->list
            ]);
        }

        return $isValid;
    }

    /**
     * @param string $label
     * @return string
     */
    public static function t($label)
    {
        return \Yii::t('fields', $label);
    }

    /**
     * @return array
     */
    public static function getOwners()
    {
        $owners = User::find()->orderBy(['CONCAT(`fname`,`lname`)' => SORT_ASC])->where([
            'uuid' => Field::find()
                ->distinct()
                ->select('{{%workflow}}.[[created_by]]')
                ->joinWith('workflow')
        ])->all();

        return ArrayHelper::map($owners, 'uuid', function (User $user) {
            return sprintf('%s <span class="text_muted">(%s)</span>', $user->getFullName(), $user->email);
        });
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return Field::getTypes();
    }
}
