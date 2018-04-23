<?php

namespace app\components\uploader;

use app\components\GlobalHelper;
use app\models\File;
use pkpudev\fasil\models\ActivityFile;
use yii\base\BaseObject;
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
	 * @param Config $config
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
		$activityType = $this->config->activityType;
		$attachment = $this->config->attachment;
		$content = $this->config->response->content;
		$fileSystem = $this->config->fileSystem;
		$targetDir = $this->config->targetDir;
		$prefix = $this->config->prefix;
		// Process Upload
		$filename = sprintf("%s_%s", $prefix, $attachment->filename);
		$fullpath = sprintf("%s/%s", $targetDir, $filename);
		$result = $fileSystem->put($fullpath, $content);

		if ($retval = $fileSystem->has($fullpath)) {
			$retval = $this->saveFile($targetDir, $filename, $activityType);
		} else {
			throw new ServerErrorHttpException("Gagal menyimpan file", 500);
		}
		
		return $retval;
	}

	/**
	 * @inheritdoc
	 */
	public function saveFile($targetDir, $filename, $activityType)
	{
		$attachment = $this->config->attachment;
		$model = $this->config->model;
		$modelName = GlobalHelper::shortClassname($model);
		$isImage = GlobalHelper::isImageFile($attachment->content_type);

		$file = new File;
		$file->file_name     = $attachment->filename;
		$file->ext           = $attachment->ext;
		$file->location      = "$targetDir/$filename";
		$file->mime_type     = $attachment->content_type;
		$file->byte_size     = $attachment->length;
		$file->created_stamp = date('Y-m-d H:i:s');
		$file->created_by    = null;

		if ($file->save()) {
			$modelFile = new ActivityFile;
			$modelFile->project_id      = $model->project_id;
			$modelFile->activity_table  = $modelName;
			$modelFile->activity_id     = $model->_id;
			$modelFile->file_id         = $file->id;
			$modelFile->file_type       = $activityType;
			$modelFile->is_image        = $isImage;
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