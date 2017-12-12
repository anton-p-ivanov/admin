<?php
namespace mail\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MailTemplateTypeFixture
 * @package mail\tests\fixtures
 */
class MailTemplateTypeFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'mail\models\TemplateType';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/templates_types.php';
    /**
     * @var array
     */
    public $depends = [
        'mail\tests\fixtures\MailTypeFixture',
        'mail\tests\fixtures\MailTemplateFixture',
    ];
}