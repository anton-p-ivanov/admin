<?php
namespace storage\actions;

use storage\models\StorageFile;
use yii\base\Action;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Class DownloadAction
 * @package storage\actions
 */
class DownloadAction extends Action
{
    /**
     * @var string|StorageFile
     */
    public $modelClass;
    /**
     * @var StorageFile
     */
    private $_model;

    /**
     * @param string $uuid
     * @param bool $original
     * @throws HttpException
     */
    public function run($uuid, $original = false)
    {
        $this->_model = ($this->modelClass)::findOne($uuid);

        if (!$this->_model) {
            throw new HttpException(404,
                \Yii::t('app', "`StorageFile` element not found."));
        }

        if (!$this->checkAccess()) {
            throw new HttpException(403, \Yii::t('app', "You don't have privileges to download this file."));
        }

        if (!$this->validateDownload()) {
            throw new HttpException(400, \Yii::t('app', 'File checksum is invalid.'));
        }

        $file = $this->_model;
        if ($stream = fopen(\Yii::$app->params['files_host'] . "/" . $file->uuid . "/download", 'rb')) {
            $name = $original ? $file->name : $file->storage->title;
            $name = preg_replace('/[\s,]/', '_', basename($name));
            $size = $file->size;
            $bytes = 1048576;

            header("Content-Type: application/octet-stream");
            header("Content-Length: $size");
            header("Content-Disposition: attachment; filename=\"$name\"");
            header("Pragma: public");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

            while (!feof($stream)) {
                echo stream_get_contents($stream, $bytes, -1);
            }

            fclose($stream);
            \Yii::$app->end();
        }

        throw new HttpException(404, "Could not get requested file.");
    }

    /**
     * @return bool
     */
    protected function validateDownload(): bool
    {
        $result = false;
        $file = $this->_model;

        if ($stream = fopen(\Yii::$app->params['files_host'] . "/" . $file->uuid . "/validate?hash=" . $file->hash, 'r')) {
            $data = stream_get_contents($stream);
            try {
                $data = Json::decode($data);
                $result = is_bool($data['valid']) && $data['valid'];
            }
            catch (\Exception $exception) {
                $result = false;
            }

            fclose($stream);
        }

        return $result;
    }

    /**
     * Check privileges before downloading file.
     * @return bool
     */
    protected function checkAccess(): bool
    {
        return true;
    }

    /**
     * Updates download statistics.
     */
    protected function updateStats(): void
    {
        return;
    }
}