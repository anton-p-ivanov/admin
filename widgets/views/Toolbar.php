<?php
/**
 * @var \yii\web\View $this
 * @var array $buttons
 * @var array $selectedButtons
 * @var array $filteredButtons
 * @var Toolbar $widget
 */

use app\widgets\Toolbar;

$widget = $this->context;

?>
<div class="<?= $widget->options['class']; ?>">

    <?php foreach ($buttons as $group): ?>
    <div class="toolbar__buttons">
        <?= $widget->renderButtons($group); ?>
    </div>
    <?php endforeach; ?>

    <div class="toolbar__filtered <?= $widget->isFiltered ? 'active' : 'hidden'; ?>">
        <span>
            <?= Toolbar::t('Showing only filtered results'); ?>
        </span>
        <?php if ($filteredButtons): ?>
            <div class="toolbar__buttons">
                <?= $widget->renderButtons($filteredButtons); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="toolbar__selected hidden">
        <span>
            <span data-selected="0">0</span> <?= Toolbar::t('item(s) selected'); ?>
        </span>
        <?php if ($selectedButtons): ?>
        <div class="toolbar__buttons">
            <?= $widget->renderButtons($selectedButtons); ?>
        </div>
        <?php endif; ?>
    </div>
</div>