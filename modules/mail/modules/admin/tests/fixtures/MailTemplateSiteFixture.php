<?php
namespace mail\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MailTemplateSiteFixture
 *
 * @package mail\modules\admin\tests\fixtures
 */
class MailTemplateSiteFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'mail\modules\admin\models\TemplateSite';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/templates_sites.php';
    /**
     * @var array
     */
    public $depends = [
        'mail\modules\admin\tests\fixtures\MailTemplateFixture',
        'mail\modules\admin\tests\fixtures\SiteFixture',
    ];
}