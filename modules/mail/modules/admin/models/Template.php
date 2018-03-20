<?php

namespace mail\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Site;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\validators\EmailValidator;

/**
 * Class Template
 *
 * @package mail\modules\admin\models
 */
class Template extends \mail\models\Template
{
    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = [
            'subject' => 'Subject',
            'code' => 'Code',
            'from' => 'From',
            'to' => 'To',
            'replyTo' => 'Reply to',
            'bcc' => 'Copy',
            'textBody' => 'Plain text',
            'htmlBody' => 'HTML',
            'format' => 'Message format',
            'type' => 'Type',
            'sites' => 'Sites',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'type' => 'Select one of available types.',
            'subject' => 'Up to 250 characters length.',
            'from' => 'Specify the sender of this template.',
            'to' => 'List the recipients of this template.',
            'replyTo' => 'List E-Mail addresses to reply to.',
            'bcc' => 'List E-Mail addresses to which send a message`s copy.',
            'sites' => 'Choose sites which can use that template.',
            'code' => 'Only latin letters, digits, dash and underscore characters are valid. Will be generated if empty.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['subject', 'to', 'type'], 'required', 'message' => self::t('{attribute} is required.')],
            ['from', 'email', 'allowName' => true, 'message' => self::t('Invalid E-Mail.')],
            [['to', 'replyTo', 'bcc'], 'validateRecipient'],
            ['subject', 'string', 'max' => 250, 'tooLong' => self::t('Maximum (max, number) characters allowed.')],
            [['textBody', 'htmlBody'], 'safe'],
            ['sites', 'exist', 'targetClass' => Site::class, 'targetAttribute' => 'uuid', 'allowArray' => true],
            ['type', 'exist', 'targetClass' => Type::class, 'targetAttribute' => 'uuid'],
            ['code', 'unique', 'message' => self::t('Template with code `{value}` is already exist.')]
        ];
    }

    /**
     * @param $attribute
     */
    public function validateRecipient($attribute)
    {
        $validator = new EmailValidator();
        $array = preg_split('/[\s,;]+/', $this->$attribute, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($array as $email) {
            if (!$validator->validate($email) && !preg_match('/^\{\{[a-z0-9_]{0,50}\}\}$/i', $email)) {
                $this->addError($attribute, self::t('Invalid E-Mail or field code.'));
            }
        }
    }

    /**
     * @return array
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::class;
        $behaviors[] = WorkflowBehavior::class;

        return $behaviors;
    }

    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        $defaultOrder = ['workflow.modified_date' => SORT_DESC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => $defaultOrder,
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            // Make symbolic code uppercase
            $this->code = mb_strtoupper($this->code);

            // Purify HTML-content
            $this->htmlBody = \Yii::$app->formatter->asHtml($this->htmlBody);
        }

        return $isValid;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->insertType();
        $this->insertSites();
    }

    /**
     * Insert template type.
     */
    protected function insertType()
    {
        TemplateType::deleteAll(['template_uuid' => $this->uuid]);

        if ($this->type) {
            (new TemplateType([
                'template_uuid' => $this->uuid,
                'type_uuid' => $this->type
            ]))->insert();
        }
    }

    /**
     * Insert templates sites.
     */
    protected function insertSites()
    {
        TemplateSite::deleteAll(['template_uuid' => $this->uuid]);

        if (is_array($this->sites)) {
            foreach ($this->sites as $site) {
                (new TemplateSite([
                    'template_uuid' => $this->uuid,
                    'site_uuid' => $site
                ]))->insert();
            }
        }
    }

    /**
     * @return Template
     */
    public function duplicate()
    {
        $copy = new self([
            'sites' => $this->sites ? ArrayHelper::getColumn($this->sites, 'uuid') : null,
            'type' => $this->type ? $this->type->uuid : null
        ]);

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $copy->$attribute = $value;
            }
        }

        $copy->code = null;

        return $copy;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        return self::find()->joinWith('workflow');
    }

    /**
     * @return array
     */
    protected static function getSortAttributes(): array
    {
        return [
            'active',
            'subject',
            'code',
            'workflow.modified_date' => [
                'asc' => ['{{%workflow}}.[[modified_date]]' => SORT_ASC],
                'desc' => ['{{%workflow}}.[[modified_date]]' => SORT_DESC],
            ],
        ];
    }
}