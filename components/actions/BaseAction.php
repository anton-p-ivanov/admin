<?php

namespace app\components\actions;

use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\widgets\ActiveForm;

/**
 * Class BaseAction
 *
 * @package app\components\actions
 */
class BaseAction extends Action
{
    /**
     * @var \yii\web\Controller
     */
    public $controller;
    /**
     * @var string
     */
    public $modelClass;
    /**
     * @var string
     */
    public $viewFile;
    /**
     * @var callable
     */
    public $afterPostHandler;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->controller->hasProperty('modelClass')) {
            $this->modelClass = $this->controller->{'modelClass'};
        }
    }

    /**
     * @param string $viewFile
     * @param array $params
     * @return string
     */
    protected function render($viewFile, $params = [])
    {
        if (\Yii::$app->request->isAjax) {
            return $this->controller->renderPartial($viewFile, $params);
        }

        return $this->controller->render($viewFile, $params);
    }

    /**
     * @return \yii\db\ActiveRecord
     * @throws BadRequestHttpException
     */
    protected function getModel()
    {
        /* @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $this->controller->{'modelClass'} ?: $this->modelClass;

        // ActiveRecord primary key
        $keys = $modelClass::primaryKey();
        $pk = array_intersect_key(\Yii::$app->request->get(), array_flip($keys));

        if (!$pk) {
            throw new BadRequestHttpException('Primary key not found in GET request.');
        }

        return method_exists($this->controller, 'getModel')
            ? $this->controller->getModel($pk)
            : $modelClass::findOne($pk);
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function post($model)
    {
        // Validate user inputs
        $errors = ActiveForm::validate($model);

        if ($errors) {
            \Yii::$app->response->statusCode = 206;
            return $errors;
        }

        $model->save(false);

        return $model->attributes;
    }
}