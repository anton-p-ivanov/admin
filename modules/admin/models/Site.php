<?php
namespace admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use yii\data\ActiveDataProvider;

/**
 * Class Site
 *
 * @package admin\models
 */
class Site extends \app\models\Site
{
    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => ['title' => SORT_ASC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes()
    {
        return (new self())->attributes();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        return self::find();
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::className();

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels =  [
            'title' => 'Title',
            'sort' => 'Sort',
            'code' => 'Code',
            'active' => 'Active',
            'url' => 'URL',
            'email' => 'E-Mail'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints =  [
            'title' => 'Up to 200 characters length.',
            'sort' => 'Sorting index. Default is 100.',
            'code' => 'Unique symbolic code.',
            'active' => 'Whether site is in use.',
            'url' => 'Web-site public URL.',
            'email' => 'Web-site contact E-Mail address.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'code', 'url', 'email'], 'required', 'message' => self::t('{attribute} is required.')],
            ['title', 'string', 'max' => 200, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['code', 'string', 'max' => 50, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['sort', 'integer', 'min' => 0, 'message' => self::t('{attribute} value must be greater than {min, number}.')],
            ['code', 'unique', 'message' => self::t('{attribute} value already in use.')],
            ['code', 'match', 'pattern' => '/^[\w\d\-\_]+$/i', 'message' => self::t('{attribute} value contains invalid characters.')],
            ['sort', 'default', 'value' => 100],
            ['active', 'boolean'],
            ['active', 'default', 'value' => 1],
            ['url', 'url', 'defaultScheme' => 'https'],
            ['email', 'email', 'allowName' => true]
        ];
    }

    /**
     * @return Site
     */
    public function duplicate()
    {
        $clone = new self();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        return $clone;
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('admin/sites', $message, $params);
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }
}
