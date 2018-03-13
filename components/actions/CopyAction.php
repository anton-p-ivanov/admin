<?php

namespace app\components\actions;

use app\models\Workflow;
use yii\web\HttpException;
use yii\widgets\ActiveForm;

/**
 * Class CopyAction
 *
 * @package app\components\actions
 */
class CopyAction extends BaseAction
{
    /**
     * @var string
     */
    public $viewFile = 'copy';
    /**
     * @var bool
     */
    public $useDeepCopy = false;

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

        // Makes a copy
        if (!method_exists($model, 'duplicate')) {
            throw new HttpException(500, 'Method `duplicate` must be declared in class `' . $model::className() . '`.');
        }

        /* @var \yii\db\ActiveRecord $copy */
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->copy($copy, $model);
        }

        if (method_exists($this->controller, 'beforeRender')) {
            call_user_func([$this->controller, 'beforeRender'], $copy);
        }

        $params = ['model' => $copy];
        if ($copy->hasAttribute('workflow_uuid')) {
            $params['workflow'] = new Workflow();
        }

        return $this->render($this->viewFile, $params);
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @param \yii\db\ActiveRecord $original
     * @return array
     */
    protected function copy($model, $original)
    {
        // Validate user inputs
        $errors = ActiveForm::validate($model);

        if ($errors) {
            \Yii::$app->response->statusCode = 206;
            return $errors;
        }

        $result = $model->save(false);

        if ($result && $this->useDeepCopy) {
            if (method_exists($this->controller, 'afterCopy')) {
                call_user_func([$this->controller, 'afterCopy'], $model, $original);
            }
        }

        return $model->attributes;
    }
}