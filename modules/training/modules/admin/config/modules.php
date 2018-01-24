<?php

$modules = [];
$path = realpath(__DIR__ . '/../modules');

if ($path === false) {
    return $modules;
}

foreach(new DirectoryIterator($path) as $item) {
    if ($item->isDir() && !$item->isDot()) {
        $name = $item->getFilename();
        $modules[$name] = "\\training\\modules\\admin\\modules\\$name\\Module";

        \Yii::setAlias("@$name", $item->getPath() . "/$name");
    }
}

return $modules;
