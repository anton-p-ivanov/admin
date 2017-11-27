<?php
namespace app\widgets\form;

/**
 * Class BaseInput
 * @package app\widgets\form
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    /**
     * @var string
     */
    public $fieldClass = 'app\widgets\form\ActiveField';
    /**
     * @var bool
     */
    public $enableClientScript = false;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param array $options
     * @return \yii\widgets\ActiveField|\app\widgets\form\ActiveField
     */
    public function field($model, $attribute, $options = [])
    {
        return parent::field($model, $attribute, $options);
    }
}