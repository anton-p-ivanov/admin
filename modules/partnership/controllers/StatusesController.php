<?php

namespace partnership\controllers;

use app\components\BaseController;
use partnership\models\Status;

/**
 * Class StatusesController
 *
 * @package partnership\controllers
 */
class StatusesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Status::class;

    /**
     * @param $key
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getModel($key)
    {
        return Status::find()->where($key)->multilingual()->one();
    }
}
