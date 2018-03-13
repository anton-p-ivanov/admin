<?php

namespace forms\modules\admin\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use fields\components\traits\Duplicator;
use forms\modules\admin\models\Form;
use forms\modules\admin\models\FormStatus;
use forms\modules\admin\modules\fields\models\Field;
use yii\base\InvalidConfigException;
use yii\filters\ContentNegotiator;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class FormsController
 *
 * @package forms\modules\admin\controllers
 */
class FormsController extends BaseController
{
    use Duplicator;
    /**
     * @var string
     */
    public $modelClass = Form::class;

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['cn'] = [
            'class' => ContentNegotiator::class,
            'only' => ['templates'],
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ]
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => IndexAction::class,
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'active' => true,
                    'active_dates' => ['active_from_date' => \Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s'))],
                    'sort' => 100,
                    'title' => \Yii::t('forms', 'New Web-form'),
                ]
            ],
            'edit' => ['class' => EditAction::class],
            'copy' => [
                'class' => CopyAction::class,
                'useDeepCopy' => (int) \Yii::$app->request->get('deepCopy') === 1
            ],
            'delete' => ['class' => DeleteAction::class],
        ];
    }

    /**
     * @param Form $model
     */
    public function beforeRender($model)
    {
        // Format dates into human readable format
        $model->formatDatesArray(['active_from_date', 'active_to_date']);
    }

    /**
     * @param string $type_uuid
     * @return array
     * @throws InvalidConfigException
     */
    public function actionTemplates($type_uuid)
    {
        /* @var \mail\models\Template $className */
        $className = '\mail\models\Template';

        if (!class_exists($className)) {
            throw new InvalidConfigException('Module `mail` seems to be missed.');
        }

        return $className::getList($type_uuid);
    }

    /**
     * @param string $form_uuid
     * @return string
     * @throws HttpException
     */
    public function actionHelp($form_uuid)
    {
        /* @var Form $model */
        $model = Form::findOne($form_uuid);

        if (!$model) {
            throw new HttpException(404, 'Form not found.');
        }

        $extra = [
            'FORM_FIELD_AUTH' => 'User authentication widget',
            'FORM_FIELD_CAPTCHA' => 'CAPTCHA widget',
        ];

        $fields = $model->getFields()
            ->filterWhere(['active' => true])
            ->orderBy(['sort' => SORT_ASC, 'code' => SORT_ASC, 'label' => SORT_DESC])
            ->select('label')
            ->indexBy('code')
            ->column();

        $fields = ArrayHelper::merge($fields, $extra);

        return $this->renderPartial('help', ['fields' => $fields]);
    }

    /**
     * @param Form $model
     * @param Form $original
     */
    public function afterCopy($model, $original)
    {
        foreach ($original->statuses as $status) {
            $this->duplicateStatus($status, $model->uuid);
        }

        foreach ($original->fields as $field) {
            $this->duplicateField($field, $model->uuid);
        }
    }

    /**
     * @param FormStatus $status
     * @param string $uuid
     * @return bool
     */
    protected function duplicateStatus($status, $uuid)
    {
        $clone = $status->duplicate();
        $clone->form_uuid = $uuid;

        return $clone->save();
    }

    /**
     * @param Field $field
     * @param string $uuid
     * @return bool
     */
    protected function duplicateField($field, $uuid)
    {
        $clone = $field->duplicate();
        $clone->form_uuid = $uuid;

        if ($clone->save()) {
            foreach ($field->fieldValidators as $validator) {
                $this->duplicateValidator($validator, $clone->uuid);
            }

            foreach ($field->fieldValues as $value) {
                $this->duplicateValue($value, $clone->uuid);
            }

            return true;
        }

        return false;
    }
}
