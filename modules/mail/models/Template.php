<?php

namespace mail\models;

use app\models\Site;
use app\models\Workflow;
use mail\helpers\Mail;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * Class Template
 *
 * @property string $uuid
 * @property string $code
 * @property string $from
 * @property string $to
 * @property string $replyTo
 * @property string $bcc
 * @property string $subject
 * @property string $textBody
 * @property string $htmlBody
 * @property string $workflow_uuid
 *
 * @property Site[] $sites
 * @property Type $type
 * @property Workflow $workflow
 *
 * @package mail\models
 */
class Template extends ActiveRecord
{
    /**
     * Message format switch.
     * @var string
     */
    public $format = 'textBody';
    /**
     * @var string
     */
    private $_type;
    /**
     * @var array
     */
    private $_sites;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%mail_templates}}';
    }

    /**
     * @param $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('mail/templates', $message, $params);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSitesRelation()
    {
        return $this->hasMany(Site::class, ['uuid' => 'site_uuid'])
            ->viaTable(TemplateSite::tableName(), ['template_uuid' => 'uuid']);
    }

    /**
     * @return Site[]
     */
    public function getSites()
    {
        if ($this->_sites === null) {
            $this->_sites = $this->getSitesRelation()->all();
        }

        return $this->_sites;
    }

    /**
     * @param array $sites
     */
    public function setSites($sites)
    {
        $this->_sites = $sites;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeRelation()
    {
        return $this->hasOne(Type::class, ['uuid' => 'type_uuid'])
            ->viaTable(TemplateType::tableName(), ['template_uuid' => 'uuid']);
    }

    /**
     * @return Type|ActiveRecord
     */
    public function getType()
    {
        if ($this->_type === null) {
            $this->_type = $this->getTypeRelation()->one();
        }

        return $this->_type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @param string $type_uuid
     * @return array
     */
    public static function getList($type_uuid)
    {
        return self::find()->joinWith('typeRelation')
            ->where(['{{%mail_types}}.[[uuid]]' => $type_uuid])
            ->select('subject')
            ->indexBy('uuid')
            ->column();
    }

    /**
     * @param string $code
     * @param array $params
     * @return bool
     * @throws NotFoundHttpException
     */
    public static function send($code, $params = [])
    {
        $template = Template::findOne(['code' => $code]);
        if (!$template) {
            throw new NotFoundHttpException('Invalid template identifier.');
        }

        $isSent = Mail::send($template, $params);
        if (!$isSent) {
            $message = 'Message with template `%s` has been composed but does not sent.';
            \Yii::debug(sprintf($message, $code), 'mail');
        }

        return $isSent;
    }
}