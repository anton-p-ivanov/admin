<?php
namespace accounts\models;

use app\models\Filter;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class AccountFilter
 *
 * @property string $uuid
 * @property string $query
 * @property string $hash
 *
 * @package accounts\models
 */
class AccountFilter extends Filter
{
    /**
     * @var string
     */
    public $owner;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $web;
    /**
     * @var boolean
     */
    public $isActive;

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
                case 'title':
                case 'email':
                case 'web':
                    $query->andFilterWhere(['like', 'title', $value]);
                    break;
                case 'isActive':
                    $query->andFilterWhere(['active' => 1]);
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
            'title' => 'Title',
            'email' => 'E-Mail',
            'web' => 'Web-site',
            'isActive' => 'Is active'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'owner' => 'User who created the form.',
            'title' => 'Account title or its part.',
            'email' => 'Account E-Mail address or its part.',
            'web' => 'Account Web-site url or its part.',
            'isActive' => 'Show accounts visible on public sites.'
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
            ['title', 'string', 'max' => 255],
            [['email', 'web'], 'string', 'max' => 100],
            ['isActive', 'boolean']
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
                'title' => $this->title,
                'email' => $this->email,
                'web' => $this->web,
                'isActive' => $this->isActive
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
        return \Yii::t('accounts', $label);
    }

    /**
     * @return array
     */
    public static function getOwners()
    {
        $owners = User::find()->orderBy(['CONCAT(`fname`,`lname`)' => SORT_ASC])->where([
            'uuid' => Account::find()
                ->distinct()
                ->select('{{%workflow}}.[[created_by]]')
                ->joinWith('workflow')
        ])->all();

        return ArrayHelper::map($owners, 'uuid', function (User $user) {
            return sprintf('%s <span class="text_muted">(%s)</span>', $user->getFullName(), $user->email);
        });
    }
}
