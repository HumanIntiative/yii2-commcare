<?php

namespace app\commands\actions;

use app\components\AttachmentCollection;
use app\components\AttachmentDownloader;
use app\components\uploader\ActivityFileUploader;
use app\components\uploader\Config;
use app\forms\FormType;
use yii\base\Action;
use yii\httpclient\Response;

class AttachmentAction extends Action
{
	use ActionTrait;

	private $authHeader;
	private $response;

	public function init()
	{
		parent::init();
		$this->authHeader = $this->controller->commcare->authHeader;
		$this->response = $this->controller->response;
	}

	public function run()
	{
		// Quick Report collection
		$collection = new AttachmentCollection($this->response, FormType::QUICK_REPORT);

		// Each Attachment
		foreach ($collection as $name=>$attachment) {
			// Check model by commcare_app_id
			$param = ['commcare_app_id'=>$attachment->app_id];
			$model = QuickReport::find()->where($param)->one();
			// Save to a folder using SCP
			if (!($model instanceof QuickReport)) continue;

			// Download an Attachment
			$downloader = new AttachmentDownloader($this->authHeader, $attachment);
			$response = $downloader->getResponse();
			// Create config for uploader
			$config = new Config;
			$config->attachment = $attachment;
			$config->fileSystem = Yii::$app->sftp;
			$config->model = $model;
			$config->prefix = (string)$model->_id;
			$config->response = $response;
			$config->targetDir = 'fasil_attachment';
			// Upload process
			$uploader = new ActivityFileUploader($config);
			$uploader->upload();

			unset($config);
		}
	}
}