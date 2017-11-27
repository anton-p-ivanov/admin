<?php
/**
 * @var \yii\web\View $this
 */
?>
<div class="modal__container">
    <div class="modal__body">
        <div class="modal__heading">Загрузка файлов <span class="hidden">завершена</span></div>
        <div>Загружается файл <span data-file-index="1">{index}</span> из <span data-file-total>{total}</span>:</div>
        <div class="progress-bar progress-bar_light" data-file="{file}">
            <div class="progress-bar__ribbon"></div>
        </div>
        <ul class="upload-errors"></ul>
    </div>
    <div class="modal__body modal__body_done hidden">
        <p class="text_center">Все выбранные файлы загружены!</p>
    </div>
    <div class="modal__footer text_center">
        <button type="button" class="btn btn_default" data-dismiss="modal">Закрыть</button>
    </div>
</div>