<?php

namespace app\themes\material\assets;

use yii\web\AssetBundle;

/**
 * Class PageAsset
 * @package app\themes\material\assets
 */
class PageAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $assetsName = 'assets';

    /**
     * Initializes the bundle.
     * If you override this method, make sure you call the parent implementation in the last.
     */
    public function init()
    {
        // Call method parent implementation
        parent::init();

        // Path to page assets
        $path = \Yii::$app->controller->viewPath . '/' . $this->assetsName;

        // Default application asset
        $this->depends[] = 'app\themes\material\assets\AppAsset';

        if (is_dir($path)) {
            $this->sourcePath = $path;

            $_view = pathinfo(\Yii::$app->controller->view->viewFile, PATHINFO_FILENAME);

            if (file_exists($this->sourcePath . '/' . $_view . '.js')) {
                $this->js[] = $_view . '.js';
            }

            if (file_exists($this->sourcePath . '/'. $_view . '.css')) {
                $this->css[] = $_view . '.css';
            }
        }
    }
}
