<?php
namespace mail\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MailTemplateSiteFixture
 * @package mail\tests\fixtures
 */
class MailTemplateSiteFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'mail\models\TemplateSite';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/templates_sites.php';
    /**
     * @var array
     */
    public $depends = [
        'mail\tests\fixtures\MailTemplateFixture',
        'mail\tests\fixtures\SiteFixture',
    ];
}