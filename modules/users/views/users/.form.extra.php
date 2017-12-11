<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\User $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>
<br>

<div class="grid">
    <div class="grid__item">
        <p class="text_center"><b><?= Yii::t('users', 'Password changes history (last 5)'); ?></b></p>

        <?= \app\widgets\grid\GridView::widget([
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->getPasswords()->limit(5),
                'pagination' => false,
                'sort' => false
            ]),
            'columns' => [
                'created_date:datetime',
                'expired_date:datetime',
            ],
            'tableOptions' => ['class' => implode(' ', [
                'grid-view__table',
                'grid-view__table_dense',
                'grid-view__table_light',
                'grid-view__table_fixed'
            ])]
        ]); ?>
    </div>
    <div class="grid__item">
        <p class="text_center"><b><?= Yii::t('users', 'Last login dates'); ?></b></p>

        <?= \app\widgets\grid\GridView::widget([
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->getSites(),
                'pagination' => false,
                'sort' => false
            ]),
            'columns' => [
                'site.title:text',
                'login_date:datetime',
            ],
            'tableOptions' => ['class' => implode(' ', [
                'grid-view__table',
                'grid-view__table_dense',
                'grid-view__table_light',
                'grid-view__table_fixed'
            ])]
        ]); ?>
    </div>
</div>
