<?php

$modules = [];
$path = realpath(__DIR__ . '/../modules');

foreach(new DirectoryIterator($path) as $item) {
    if ($item->isDir() && !$item->isDot()) {
        $name = $item->getFilename();
        $modules[$name] = "\\forms\\modules\\admin\\modules\\$name\\Module";

        \Yii::setAlias("@$name", $item->getPath() . "/$name");
    }
}

return $modules;
