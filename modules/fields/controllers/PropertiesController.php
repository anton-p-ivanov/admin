<?php

namespace fields\controllers;

use app\models\User;
use storage\models\StorageTree;
use yii\filters\AjaxFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class PropertiesController
 *
 * @package fields\controllers
 */
class PropertiesController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if (YII_DEBUG && \Yii::$app->user->isGuest) {
            \Yii::$app->user->login(User::findOne(['email' => 'guest.user@example.com']));
        }

        if (\Yii::$app->request->isPost) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ajax'] = [
            'class' => AjaxFilter::class,
            'except' => ['index']
        ];

        return $behaviors;
    }

    /**
     * @param $tree_uuid
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetFileInfo($tree_uuid)
    {
        $tree = StorageTree::findOne(['tree_uuid' => $tree_uuid]);

        if (!$tree) {
            throw new NotFoundHttpException('Invalid tree identifier.');
        }

        return $this->renderPartial('@app/widgets/form/views/File.Info.php', [
            'file' => $tree->storage->file
        ]);
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function postUpdate($model)
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
