<?php

namespace mail\modules\admin\controllers;

use app\components\BaseController;
use mail\modules\admin\models\Template;

/**
 * Class TemplatesController
 *
 * @package mail\modules\admin\controllers
 */
class TemplatesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Template::class;
}
