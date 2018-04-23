<?php

namespace app\commands\actions;

use app\components\AttachmentCollection;
use app\components\AttachmentDownloader;
use app\components\uploader\ActivityFileUploader;
use app\components\uploader\Config;
use app\forms\FormType;
use pkpudev\fasil\models\News;
use pkpudev\fasil\models\QuickReport;
use pkpudev\fasil\models\Story;
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
		$types = [FormType::QUICK_REPORT, FormType::BERITA_AKTIFITAS, FormType::CERITA_HUMANIS];

		foreach ($types as $formType) {
			$this->uploadCollection($formType);
		}
	}

	protected function uploadCollection($formType)
	{
		// Quick Report collection
		$collection = new AttachmentCollection($this->response, $formType);

		// Each Attachment
		foreach ($collection as $name=>$attachment) {
			// Check model by commcare_app_id
			$model = $this->findModel($formType, $attachment->app_id);
			if (!($model instanceof ActiveRecordInterface)) continue;

			// Download an Attachment
			$downloader = new AttachmentDownloader($this->authHeader, $attachment);
			$response = $downloader->getResponse();

			// Create config for uploader
			$config = new Config;
			$config->activityType = FormType::getActivityType($formType);
			$config->attachment   = $attachment;
			$config->fileSystem   = \Yii::$app->sftp;
			$config->model        = $model;
			$config->prefix       = (string)$model->_id;
			$config->response     = $response;

			// Upload process
			$uploader = new ActivityFileUploader($config);
			$retval = $uploader->upload();

			unset($config);
		}
	}

	protected function findModel($formType, $appId)
	{
		// Check model by commcare_app_id
		$model = null;
		$param = ['commcare_app_id'=>$attachment->app_id];
		switch ($formType) {
			case FormType::QUICK_REPORT :
				$model = QuickReport::find()->where($param)->one();
				break;
			case FormType::BERITA_AKTIFITAS :
				$model = News::find()->where($param)->one();
				break;
			case FormType::CERITA_HUMANIS :
				$model = Story::find()->where($param)->one();
				break;
		}
		return $model;
	}
}