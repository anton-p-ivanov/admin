<?php

namespace i18n\modules\admin\controllers;

use app\components\BaseController;
use i18n\modules\admin\models\Language;

/**
 * Class LanguagesController
 *
 * @package i18n\modules\admin\controllers
 */
class LanguagesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Language::class;
}
