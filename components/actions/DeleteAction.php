<?php

namespace app\components\actions;

/**
 * Class DeleteAction
 *
 * @package app\components\actions
 */
class DeleteAction extends BaseAction
{
    /**
     * @var string
     */
    public $postAttributeName = 'selection';

    /**
     * @return bool
     */
    public function run()
    {
        /* @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $this->modelClass;

        if (method_exists($modelClass, 'getSelected')) {
            $selected = call_user_func([$modelClass, 'getSelected']);
        }
        else {
            $selected = $this->getSelected();
        }

        $models = $modelClass::findAll($selected);

        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @return array|mixed
     */
    protected function getSelected()
    {
        /* @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $this->modelClass;

        // ActiveRecord primary key
        $keys = $modelClass::primaryKey();
        $pk = array_intersect_key(\Yii::$app->request->get(), array_flip($keys));

        return \Yii::$app->request->post($this->postAttributeName, $pk);
    }
}