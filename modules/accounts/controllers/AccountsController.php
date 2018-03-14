<?php

namespace accounts\controllers;

use accounts\components\traits\Duplicator;
use accounts\models\Account;
use app\components\BaseController;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Class AccountsController
 * @package accounts\controllers
 */
class AccountsController extends BaseController
{
    use Duplicator;

    /**
     * @var string
     */
    public $modelClass = Account::class;

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['cn'] = [
            'class' => ContentNegotiator::class,
            'only' => ['list'],
            'formats' => ['application/json' => Response::FORMAT_JSON]
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['copy']['useDeepCopy'] = (int) \Yii::$app->request->get('deep') === 1;
        $actions['create']['modelConfig'] = [
            'active' => true,
        ];

        return $actions;
    }

    /**
     * This method searches for accounts by their name or its part.
     * Used in select boxes.
     *
     * @param string $search
     * @return array
     */
    public function actionList($search = '')
    {
        $query = Account::find()->where(['like', 'title', $search]);

        if (\Yii::$app->request->method === 'OPTIONS') {
            $count = $query->count();
            if ($count > 50) {
                return ['count' => $count];
            }
        }

        return $query
            ->select('title')
            ->indexBy('uuid')
            ->orderBy('title')
            ->column();
    }

    /**
     * @param Account $model
     * @param Account $original
     */
    public function afterCopy($model, $original)
    {
        $relations = ['contacts', 'managers', 'statuses'];
        foreach ($relations as $name) {
            $name = 'account' . ucfirst($name);
            foreach ($original->$name as $relation) {
                $this->cloneRelation($relation, $model->uuid);
            }
        }

        // Cloning addresses
        $this->cloneAddresses($model, $original);

        // Cloning custom fields
        $this->cloneProperties($model, $original);
    }
}
