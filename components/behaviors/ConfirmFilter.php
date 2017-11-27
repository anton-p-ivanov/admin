<?php

namespace app\components\behaviors;

use yii\base\ActionEvent;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class ConfirmFilter
 * @package app\components\behaviors
 */
class ConfirmFilter extends VerbFilter
{
    /**
     * @param ActionEvent $event
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function beforeAction($event)
    {
        $action = $event->action->id;

        if (in_array($action, $this->actions)) {
            $password = \Yii::$app->request->post('password');

            if (!$password) {
                $this->error('Password is required to confirm user action.');
            }

//            if (!User::validatePassword(\Yii::$app->user->identity, $password)) {
//                $this->error('Password is not valid.');
//            }
        }

        return $event->isValid;
    }

    /**
     * @param string $message
     * @throws ForbiddenHttpException
     */
    protected function error($message)
    {
        if (\Yii::$app->request->isAjax) {
            // Get response object
            $response = \Yii::$app->response;

            // Set response object properties
            $response->statusCode = 206;
            $response->format = Response::FORMAT_JSON;
            $response->data = ['password' => [\Yii::t('app', $message)]];

            // Send response
            $response->send();

            // End application
            \Yii::$app->end();
        }

        throw new ForbiddenHttpException($message);
    }
}