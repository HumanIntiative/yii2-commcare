<?php

namespace app\components\uploader;

use app\components\GlobalHelper;
use app\models\File;
use pkpudev\fasil\models\ActivityFile;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\db\Exception;
use yii\web\ServerErrorHttpException;

class ActivityFileUploader extends BaseObject
{
	/**
	 * @var Config
	 */
	private $config;

	/**
	 * Construct
	 * 
	 * @param ActiveRecordInterface $model
	 * @param Response $response
	 */
	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	/**
	 * @inheritdoc
	 */
	public function upload()
	{
		// Process Upload
		$content = $this->config->response->content;
		$targetDir = $this->config->targetDir;
		$filename = sprintf("%s_%s", $this->config->prefix, $this->config->attachment->filename);
		$fullpath = sprintf("%s/%s", $targetDir, $filename);
		$result = $this->config->fileSystem->put($fullpath, $content);

		if ($retval = $this->config->fileSystem->has($fullpath)) {
			$retval = $this->saveFile($uploaded, $filename, $targetDir, $this->config->type);
		} else {
			throw new ServerErrorHttpException("Gagal menyimpan file", 500);
		}
		
		return $retval;
	}

	/**
	 * @inheritdoc
	 */
	public function saveFile($uploaded, $filename, $targetDir, $desc=null)
	{
		$modelName = GlobalHelper::shortClassname($this->config->model);
		$isImage = GlobalHelper::isImageFile($uploaded->type);

		$file = new File;
		$file->file_name     = $uploaded->name;
		$file->ext           = $uploaded->extension;
		$file->location      = "$targetDir/$filename";
		$file->mime_type     = $uploaded->type;
		$file->byte_size     = $uploaded->size;
		$file->metadata      = json_encode(['error'=>$uploaded->error]);
		$file->created_stamp = date('Y-m-d H:i:s');
		$file->created_by    = null;

		if ($file->save()) {
			$modelFile = new ActivityFile;
			$modelFile->project_id = $this->config->model->project_id;
			$modelFile->activity_table = $modelName;
			$modelFile->activity_id = $this->config->model->_id;
			$modelFile->file_id = $file->id;
			$modelFile->file_type = $desc;
			$modelFile->is_image = $isImage;
			if ($modelFile->save()) {
				return true;
			} else {
				throw new Exception(json_encode($modelFile->errors));
			}
		} else {
			throw new Exception(json_encode($file->errors));
		}
	}
}