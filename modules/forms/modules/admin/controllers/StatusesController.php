<?php

namespace forms\modules\admin\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\BaseController;
use forms\modules\admin\models\Form;
use forms\modules\admin\models\FormStatus;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Class StatusesController
 *
 * @package forms\modules\admin\controllers
 */
class StatusesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = FormStatus::class;
    /**
     * @var Form
     */
    private $_form;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            if (!($form_uuid = \Yii::$app->request->get('form_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!($this->_form = Form::findOne($form_uuid))) {
                throw new HttpException(404, 'Form not found.');
            }
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'active' => true,
                    'sort' => 100,
                    'form_uuid' => \Yii::$app->request->get('form_uuid')
                ]
            ],
            'edit' => EditAction::class,
            'copy' => CopyAction::class,
            'delete' => DeleteAction::class,
        ];
    }

    /**
     * @param string $form_uuid
     * @return string
     */
    public function actionIndex($form_uuid)
    {
        $params = [
            'dataProvider' => FormStatus::search($form_uuid),
            'defaultStatus' => $this->_form->getDefaultStatus(),
            'form' => $this->_form
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }
}
