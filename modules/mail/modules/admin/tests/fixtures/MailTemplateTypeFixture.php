<?php
namespace mail\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MailTemplateTypeFixture
 *
 * @package mail\modules\admin\tests\fixtures
 */
class MailTemplateTypeFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'mail\modules\admin\models\TemplateType';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/templates_types.php';
    /**
     * @var array
     */
    public $depends = [
        'mail\modules\admin\tests\fixtures\MailTypeFixture',
        'mail\modules\admin\tests\fixtures\MailTemplateFixture',
    ];
}