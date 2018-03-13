<?php

namespace app\components\actions;

use yii\data\BaseDataProvider;
use yii\web\HttpException;

/**
 * Class IndexAction
 *
 * @package app\components\actions
 */
class IndexAction extends BaseAction
{
    /**
     * Additional params send to view.
     * @var array
     */
    public $params = [];
    /**
     * @var string
     */
    public $viewFile = 'index';

    /**
     * @inheritdoc
     */
    public function run()
    {
        /* @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $this->modelClass;

        if (is_callable($this->params)) {
            $this->params = call_user_func($this->params);
        }

        if (
            !array_key_exists('dataProvider', $this->params) ||
            !($this->params['dataProvider'] instanceof BaseDataProvider)
        ) {
            if (!method_exists($modelClass, 'search')) {
                throw new HttpException(500, sprintf('Method `search` must be implemented in class `%s`', $modelClass));
            }

            $this->params['dataProvider'] = $modelClass::search();
        }

        if (\Yii::$app->request->isAjax) {
            return $this->controller->renderPartial($this->viewFile, $this->params);
        }

        return $this->render($this->viewFile, $this->params);
    }
}