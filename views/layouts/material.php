<?php
/**
 * @var $this \yii\web\View
 * @var $content string
 */

use yii\helpers\Html;

\app\themes\material\assets\AppAsset::register($this);
\app\widgets\ToolbarAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header>
    <div class="toolbar toolbar_header">
        <div class="toolbar__buttons">
            <a href="#" class="toolbar-btn">
                <i class="material-icons">menu</i>
            </a>
        </div>
        <div class="toolbar__title">
            <?= $this->title; ?>
        </div>
        <div class="toolbar__buttons">
            <a href="<?= \yii\helpers\Url::to(['help']); ?>" class="toolbar-btn">
                <i class="material-icons">help</i>
            </a>
        </div>
    </div>
</header>
<nav>
    <div class="app-logo">
        <i class="app-logo__logo material-icons">dashboard</i>
        <span class="app-logo__title">Application Title</span>
    </div>
</nav>
<main>
    <?= $content; ?>
</main>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
