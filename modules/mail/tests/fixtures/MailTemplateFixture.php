<?php
namespace mail\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MailTemplateFixture
 * @package mail\tests\fixtures
 */
class MailTemplateFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'mail\models\Template';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/templates.php';
    /**
     * @var array
     */
    public $depends = [
        'mail\tests\fixtures\MailTypeFixture'
    ];
}