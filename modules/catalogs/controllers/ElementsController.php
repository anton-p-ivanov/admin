<?php

namespace catalogs\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use catalogs\models\Catalog;
use catalogs\models\Element;
use catalogs\models\ElementTree;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ElementsController
 *
 * @package catalogs\controllers
 */
class ElementsController extends Controller
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
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['delete'],
            ]
        ];
        $behaviors['confirm'] = [
            'class' => ConfirmFilter::className(),
            'actions' => ['delete']
        ];
        $behaviors['ajax'] = [
            'class' => AjaxFilter::className(),
            'except' => ['index']
        ];
        $behaviors['json'] = [
            'class' => ContentNegotiator::className(),
            'only' => ['get-canonical-path'],
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];

        return $behaviors;
    }

    /**
     * @param string $tree_uuid
     * @return string
     */
    public function actionIndex($tree_uuid)
    {
        /* @var Catalog $catalog */
        /* @var ElementTree $parentNode */
        /* @var ElementTree $currentNode */
        extract($this->loadModels($tree_uuid));

        $params = [
            'dataProvider' => ElementTree::search(['tree_uuid' => $currentNode->tree_uuid]),
            'currentNode' => $currentNode,
            'parentNode' => $parentNode,
            'catalog' => $catalog
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param string $tree_uuid
     * @param string $type
     * @return array|string
     */
    public function actionCreate($tree_uuid, $type = Element::ELEMENT_TYPE_ELEMENT)
    {
        /* @var Catalog $catalog */
        /* @var ElementTree $parentNode */
        /* @var ElementTree $currentNode */
        extract($this->loadModels($tree_uuid));

        $model = new Element([
            'active' => true,
            'active_dates' => ['active_from_date' => \Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s'))],
            'catalog_uuid' => $catalog->uuid,
            'locations' => [$currentNode->tree_uuid],
            'type' => $type,
            'sort' => 100
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        // Format dates into human readable format
        $model->formatDatesArray();

        return $this->renderPartial('create', [
            'model' => $model,
            'workflow' => new Workflow()
        ]);
    }

    /**
     * @param string $uuid
     * @param string $tree_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid, $tree_uuid)
    {
        /* @var Element $model */
        $model = Element::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Element or section not found.');
        }

        /* @var Catalog $catalog */
        /* @var ElementTree $parentNode */
        /* @var ElementTree $currentNode */
        extract($this->loadModels($tree_uuid));

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        // Format dates into human readable format
        $model->formatDatesArray();

        return $this->renderPartial('edit', [
            'model' => $model,
            'workflow' => $model->workflow ?: new Workflow()
        ]);
    }

    /**
     * @param string $uuid
     * @param string $tree_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid, $tree_uuid)
    {
        /* @var Element $model */
        $model = Element::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Element or section not found.');
        }

        /* @var Catalog $catalog */
        /* @var ElementTree $parentNode */
        /* @var ElementTree $currentNode */
        extract($this->loadModels($tree_uuid));

        // Makes a model`s copy
        $copy = $model->duplicate();
        $copy->locations = [$parentNode->tree_uuid];

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy);
        }

        // Format dates into human readable format
        $copy->formatDatesArray();

        return $this->renderPartial('copy', [
            'model' => $copy,
            'workflow' => new Workflow()
        ]);
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('tree_uuid'));
        $models = ElementTree::findAll(['tree_uuid' => $selected]);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param string $tree_uuid
     * @return array
     * @throws HttpException
     */
    public function actionGetCanonicalPath($tree_uuid)
    {
        $node = ElementTree::findOne(['tree_uuid' => $tree_uuid]);

        if (!$node) {
            throw new HttpException(404, 'Invalid tree node.');
        }

        return $node->getCanonicalPath();
    }

    /**
     * @param string $tree_uuid
     * @return array
     * @throws HttpException
     */
    protected function loadModels($tree_uuid)
    {
        /* @var ElementTree $currentNode */
        $currentNode = ElementTree::findOne(['tree_uuid' => $tree_uuid]);

        if (!$currentNode) {
            throw new HttpException(404, 'Invalid tree node.');
        }

        /* @var Catalog $catalog */
        $catalog = $currentNode->isRoot()
            ? Catalog::findOne(['tree_uuid' => $currentNode->tree_uuid])
            : $currentNode->element->catalog;

        if (!$catalog) {
            throw new HttpException(404, 'Invalid catalog.');
        }

        $parentNode = $currentNode->isRoot() ? null : $currentNode->parents(1)->one();

        return [
            'currentNode' => $currentNode,
            'parentNode' => $parentNode,
            'catalog' => $catalog
        ];
    }

    /**
     * @param Element $model
     * @return array
     */
    protected function postCreate($model)
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
