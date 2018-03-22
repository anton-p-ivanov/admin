<?php

namespace mail\helpers;

use app\models\Site;
use mail\models\Template;
use yii\base\BaseObject;

/**
 * Class Mail
 *
 * @property string $from
 * @property string $to
 * @property string $replyTo
 * @property string $subject
 * @property string $htmlBody
 * @property string $textBody
 * @property string $bcc
 *
 * @property \app\models\Site $clientSite
 *
 * @package mail\helpers
 */
class Mail extends BaseObject
{
    /**
     * @var Template
     */
    public $template;
    /**
     * @var array
     */
    public $attributes = [
        'from', 'to', 'replyTo', 'subject', 'htmlBody', 'textBody', 'bcc'
    ];
    /**
     * @var \app\models\Site
     */
    private $_clientSite;
    /**
     * @var \app\models\Site
     */
    private $_adminSite;

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, $this->attributes) && $this->template->hasAttribute($name)) {
            $value = $this->template->{$name};

            if (!$value) {
                if ($name === 'from') {
                    $value = $this->clientSite ? $this->clientSite->email : null;
                }
                else if ($name === 'replyTo') {
                    $value = $this->from;
                }
            }

            return $value;
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->attributes) && $this->template->hasAttribute($name)) {
            $this->template->{$name} = $value;
        }
        else {
            parent::__set($name, $value);
        }
    }

    /**
     * @param Template $template
     * @param array $params
     * @return bool
     */
    public static function send(Template $template, $params)
    {
        $mail = new self(['template' => $template]);

        $renderer = new \Twig_Environment();
        $renderer->setLoader(new \Twig_Loader_Array($mail->template->attributes));

        // Add site params
        foreach (['client', 'admin'] as $type) {
            if ($mail->{$type . 'Site'}) {
                $mail->setParams($params, $mail->{$type . 'Site'}->attributes, $type);
            }
        }

        // Render mail fields
        foreach ($mail->attributes as $attribute) {
            $mail->$attribute = $renderer->render($attribute, $params);
        }

        // Parse named E-mail addresses to comply RFC 2822, 3.6.2.
        foreach (['from', 'replyTo'] as $attribute) {
            if (preg_match('/^(.+)\<(.+)\>$/', $mail->$attribute, $matches)) {
                $mail->$attribute = [$matches[2] => $matches[1]];
            }
        }

        foreach (['to', 'replyTo'] as $attribute) {
            if (is_string($mail->$attribute)) {
                $mail->$attribute = preg_split('/[\s,;]+/', $mail->$attribute, -1, PREG_SPLIT_NO_EMPTY);
            }
        }

        return $mail->sendInternal();
    }

    /**
     * @return \app\models\Site
     */
    public function getClientSite()
    {
        if ($this->_clientSite === null) {
            $clientId = \Yii::$app->request->headers->get('client-id');
            if ($clientId && array_key_exists($clientId, $this->template->sites)) {
                $this->_clientSite = $this->template->sites[$clientId];
            }
            else {
                $this->_clientSite = Site::findOne(['uuid' => \Yii::$app->id]);
            }
        }

        return $this->_clientSite;
    }

    /**
     * @return \app\models\Site
     */
    public function getAdminSite()
    {
        if ($this->_adminSite === null) {
            $this->_adminSite = Site::findOne(['uuid' => \Yii::$app->id]);
        }

        return $this->_adminSite;
    }

    /**
     * @return bool
     */
    public function sendInternal()
    {
        $mailer = \Yii::$app->mailer->compose();

        foreach ($this->attributes as $attribute) {
            if (!$this->$attribute) {
                continue;
            }

            $mailer->{'set' . ucfirst($attribute)}($this->$attribute);
        }

        return $mailer->send();
    }

    /**
     * @param array $params
     * @param array $attributes
     * @param string $prefix
     */
    protected function setParams(&$params, $attributes, $prefix = '')
    {
        foreach ($attributes as $key => $value) {
            $params[strtoupper(($prefix ? ($prefix . '_') : '') . $key)] = $value;
        }
    }
}