<?php

namespace app\components\actions;

use app\models\Workflow;

/**
 * Class CreateAction
 *
 * @package app\components\actions
 */
class CreateAction extends BaseAction
{
    /**
     * @var string
     */
    public $viewFile = 'create';
    /**
     * Array of initial model values.
     * @var array
     */
    public $modelConfig = [];

    /**
     * @return array|string
     */
    public function run()
    {
        $modelClass = $this->controller->{'modelClass'} ?: $this->modelClass;

        /* @var \yii\db\ActiveRecord $model */
        $model = new $modelClass($this->modelConfig);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->post($model);
        }

        $params = ['model' => $model];
        if ($model->hasAttribute('workflow_uuid')) {
            $params['workflow'] = new Workflow();
        }

        return $this->render($this->viewFile, $params);
    }
}