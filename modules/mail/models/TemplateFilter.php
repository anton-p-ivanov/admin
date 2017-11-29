<?php
namespace mail\models;

use app\models\Filter;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class TemplateFilter
 *
 * @property string $uuid
 * @property string $query
 * @property string $hash
 *
 * @package mail\models
 */
class TemplateFilter extends Filter
{
    /**
     * @var string
     */
    public $owner;
    /**
     * @var string
     */
    public $subject;
    /**
     * @var string
     */
    public $from;
    /**
     * @var string
     */
    public $to;
    /**
     * @var string
     */
    public $code;

    /**
     * @inheritdoc
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
                case 'subject':
                case 'code':
                case 'from':
                case 'to':
                    $query->andFilterWhere(['like', $attribute, $value]);
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
            'subject' => 'Subject',
            'code' => 'Code',
            'from' => 'From',
            'to' => 'To',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'owner' => 'User who created the template.',
            'subject' => 'Mail subject or its part.',
            'code' => 'Template unique code or its part.',
            'from' => 'Filter by sender.',
            'to' => 'Filter by recipient.',
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
            [['subject', 'from', 'to'], 'string', 'max' => 250, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['code', 'string', 'max' => 100, 'message' => self::t('Maximum {max, number} characters allowed.')],
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
                'subject' => $this->subject,
                'code' => $this->code,
                'from' => $this->from,
                'to' => $this->to,
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
        return \Yii::t('mail', $label);
    }

    /**
     * @return array
     */
    public static function getOwners()
    {
        $owners = User::find()->orderBy(['CONCAT(`fname`,`lname`)' => SORT_ASC])->where([
            'uuid' => Template::find()
                ->distinct()
                ->select('{{%workflow}}.[[created_by]]')
                ->joinWith('workflow')
        ])->all();

        return ArrayHelper::map($owners, 'uuid', function (User $user) {
            return sprintf('%s <span class="text_muted">(%s)</span>', $user->getFullName(), $user->email);
        });
    }
}
