<?php

namespace storage\helpers;

/**
 * Class FileHelper
 * @package storage\helpers
 */
class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * Removes file from file system
     * @param mixed $file absolute file path
     */
    public static function removeFile($file)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \Yii::$app->params['files_host'] . "/delete");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['uuid' => $file]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Origin: http://' . $_SERVER['HTTP_HOST']]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close ($ch);
    }
}
