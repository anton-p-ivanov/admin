<?php

namespace mail\modules\admin\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\BaseController;
use mail\modules\admin\models\TemplateType;
use mail\modules\admin\models\Type;

/**
 * Class TypesController
 *
 * @package mail\modules\admin\controllers
 */
class TypesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Type::class;

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'create' => CreateAction::class,
            'edit' => EditAction::class,
            'copy' => CopyAction::class,
            'delete' => DeleteAction::class,
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = Type::search();

        $params = [
            'dataProvider' => $dataProvider,
            'templates' => TemplateType::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('type_uuid')
                ->indexBy('type_uuid')->column()
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }
}
