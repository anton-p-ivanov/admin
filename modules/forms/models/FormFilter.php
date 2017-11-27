<?php
namespace forms\models;

use app\models\Filter;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class FormFilter
 *
 * @property string $uuid
 * @property string $query
 * @property string $hash
 *
 * @package forms\models
 */
class FormFilter extends Filter
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
    public $code;
    /**
     * @var boolean
     */
    public $withResults;
    /**
     * @var boolean
     */
    public $inUse;

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
                case 'title':
                    $query->andFilterWhere(['like', 'title', $value]);
                    break;
                case 'code':
                    $query->andFilterWhere(['like', 'code', $value]);
                    break;
                case 'withResults':
                    $query->joinWith('results')->andWhere('{{%forms_results}}.[[uuid]] IS NOT NULL');
                    break;
                case 'inUse':
                    $sql = 'NOW() BETWEEN {{%forms}}.[[active_from_date]] AND {{%forms}}.[[active_to_date]]';
                    $query->andFilterWhere(['active' => 1])->andWhere($sql);
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
            'owner' => self::t('Owner'),
            'title' => self::t('Title'),
            'code' => self::t('Code'),
            'withResults' => self::t('With results'),
            'inUse' => self::t('In use')
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'owner' => 'User who uploaded the file.',
            'title' => 'Form title or its part.',
            'code' => 'Form unique code or its part.',
            'withResults' => 'Show forms with results only.',
            'inUse' => 'Show forms visible on public sites.'
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
            ['code', 'string', 'max' => 50],
            [['withResults', 'inUse'], 'boolean']
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
                'code' => $this->code,
                'withResults' => $this->withResults,
                'inUse' => $this->inUse
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
        return \Yii::t('forms', $label);
    }

    /**
     * @return array
     */
    public static function getOwners()
    {
        $owners = User::find()->orderBy(['CONCAT(`fname`,`lname`)' => SORT_ASC])->where([
            'uuid' => Form::find()
                ->distinct()
                ->select('{{%workflow}}.[[created_by]]')
                ->joinWith('workflow')
        ])->all();

        return ArrayHelper::map($owners, 'uuid', function (User $user) {
            return sprintf('%s <span class="text_muted">(%s)</span>', $user->getFullName(), $user->email);
        });
    }
}
