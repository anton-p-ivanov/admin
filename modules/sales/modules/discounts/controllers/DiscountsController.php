<?php

namespace sales\modules\discounts\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\BaseController;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Class DiscountsController
 *
 * @package sales\modules\discounts\controllers
 */
class DiscountsController extends BaseController
{
    /**
     * @var \sales\modules\discounts\models\StatusDiscount
     */
    public $modelClass;

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'status_uuid' => \Yii::$app->request->get('status_uuid'),
                    'dates' => ['issue_date' => \Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s'))],
                ]
            ],
            'edit' => ['class' => EditAction::class],
            'copy' => ['class' => CopyAction::class],
            'delete' => ['class' => DeleteAction::class],
        ];
    }

    /**
     * @param \sales\modules\discounts\models\StatusDiscount $model
     */
    public function beforeRender($model)
    {
        // Format dates into human readable format
        $model->formatDatesArray(['issue_date', 'expire_date']);

        // Format percent value
        $model->value = (double) $model->value * 100;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            $status_uuid = \Yii::$app->request->get('status_uuid');

            if (!$status_uuid) {
                throw new BadRequestHttpException();
            }

            $modelClass = $this->modelClass;
            $status = ($modelClass::$statusModel)::findOne($status_uuid);

            if (!$status) {
                throw new HttpException(404, 'Status not found.');
            }
        }

        return $isValid;
    }

    /**
     * @param string $status_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($status_uuid)
    {
        $modelClass = $this->modelClass;
        $status = ($modelClass::$statusModel)::findOne($status_uuid);

        if (!$status) {
            throw new HttpException(404, 'Status not found.');
        }

        $params = [
            'dataProvider' => $this->modelClass::search($status_uuid),
            'status' => $status
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }
}
