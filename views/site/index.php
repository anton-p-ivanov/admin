<?php
/**
 * @var $this \yii\web\View
 */
?>
<div style="width:600px;margin:30px;">
    <p>
        Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum
        используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное
        распределение букв и пробелов в абзацах, которое не получается при простой дубликации "Здесь ваш текст.. Здесь
        ваш текст.. Здесь ваш текст.."
    </p>
    <p>
        Многие программы электронной вёрстки и редакторы HTML используют Lorem Ipsum в
        качестве текста по умолчанию, так что поиск по ключевым словам "lorem ipsum" сразу показывает, как много
        веб-страниц всё ещё дожидаются своего настоящего рождения. За прошедшие годы текст Lorem Ipsum получил много
        версий. Некоторые версии появились по ошибке, некоторые - намеренно (например, юмористические варианты).
    </p>
    <ul>
        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
        <li>Praesent et purus id ante porta convallis.</li>
        <li>Integer at neque facilisis, hendrerit neque quis, fringilla augue.</li>
        <li>Integer facilisis lacus et ex scelerisque sodales.</li>
        <li>Pellentesque pulvinar tortor at nulla sodales, pellentesque placerat est efficitur.</li>
        <li>Vestibulum at nibh faucibus, vehicula magna malesuada, maximus tellus.</li>
    </ul>

    <a href="#" class="btn" data-toggle="modal" data-target="#modal">Button</a>
    <a href="#" class="btn btn_primary" data-toggle="modal" data-target="#modal">Button</a>

</div>
<div id="modal" class="modal" role="dialog">
    <div class="modal__container">
        <div class="modal__header">
            Modal dialog heading ...
        </div>
        <div class="modal__body">
            Многие программы электронной вёрстки и редакторы HTML используют Lorem Ipsum в
            качестве текста по умолчанию, так что поиск по ключевым словам "lorem ipsum" сразу показывает, как много
            веб-страниц всё ещё дожидаются своего настоящего рождения. За прошедшие годы текст Lorem Ipsum получил много
            версий. Некоторые версии появились по ошибке, некоторые - намеренно (например, юмористические варианты).
        </div>
        <div class="modal__footer">
            <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
            <button type="button" class="btn btn_primary" data-dismiss="modal">Выполнить</button>
        </div>
    </div>
</div>