<?php

namespace sales\modules\discounts\controllers;

use app\components\BaseController;
use sales\modules\discounts\models\Discount;

/**
 * Class TypesController
 *
 * @package sales\modules\discounts\controllers
 */
class TypesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Discount::class;

    /**
     * @param Discount $model
     */
    public function beforeRender($model)
    {
        $model->value = (double) $model->value * 100;
    }
}
