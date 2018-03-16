<?php
namespace app\widgets\form;

use storage\models\StorageFile;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class File
 *
 * @package app\widgets\form
 */
class File extends InputWidget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Registering widget assets
        FileAsset::register($this->view);
    }

    /**
     * @return string
     */
    public function run()
    {
        $file = null;
        $value = Html::getAttributeValue($this->model, $this->attribute);

        if ($value) {
            $file = StorageFile::findOne($value);
        }

        return $this->render('File', ['file' => $file ?: null]);
    }
}
