<?php

namespace app\components\actions;

use app\models\Workflow;
use yii\web\HttpException;

/**
 * Class EditAction
 *
 * @package app\components\actions
 */
class EditAction extends BaseAction
{
    /**
     * @var string
     */
    public $viewFile = 'edit';

    /**
     * @return array|string
     * @throws HttpException
     */
    public function run()
    {
        /* @var \yii\db\ActiveRecord $model */
        $model = $this->getModel();

        if (!$model) {
            throw new HttpException(404, 'Not found.');
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->post($model);
        }

        if (method_exists($this->controller, 'beforeRender')) {
            call_user_func([$this->controller, 'beforeRender'], $model);
        }

        $params = ['model' => $model];
        if ($model->hasAttribute('workflow_uuid')) {
            $params['workflow'] = $model->{'workflow'} ?: new Workflow();
        }

        return $this->render($this->viewFile, $params);
    }
}