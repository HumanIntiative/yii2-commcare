<?php

namespace app\commands\actions;

use app\components\AttachmentCollection;
use app\components\AttachmentDownloader;
use app\components\uploader\ActivityFileUploader;
use app\components\uploader\Config;
use app\forms\Attachment;
use app\forms\FormType;
use app\models\ProjectBeneficiary;
use pkpudev\fasil\models\ActivityFile;
use pkpudev\fasil\models\News;
use pkpudev\fasil\models\QuickReport;
use pkpudev\fasil\models\Story;
use yii\base\Action;
use yii\db\ActiveRecordInterface;
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
		$types = [
			FormType::QUICK_REPORT,
			FormType::BERITA_AKTIFITAS,
			FormType::CERITA_HUMANIS,
			FormType::PM_DETAIL
		];

		foreach ($types as $formType) {
			$this->uploadCollection($formType);
		}
	}

	protected function uploadCollection($formType)
	{
		// Quick Report collection
		echo '--- Generating collection '.$formType.'...'."\r\n";
		$collection = new AttachmentCollection($this->response, $formType);
		echo '--- Finish generating collection : '.count($collection)."\r\n";

		// Each Attachment
		echo '--- Insert to database ...'."\r\n"; $count = 0;
		foreach ($collection as $name=>$attachment) {
			// Check PMP
			$projectId = $this->findProjectId($attachment->pmp);
			if (null === $projectId) continue;
			// Check model by commcare_app_id
			$model = $this->findModel($formType, $attachment->app_id);
			if (!($model instanceof ActiveRecordInterface)) continue;
			// Check file already uploaded
			if ($this->fileExists($model, $attachment)) continue;

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
			if ($uploader->upload()) $count++;

			unset($config);
		}
		echo '--- Finish insert data : '.$count."\r\n";
	}

	protected function findModel($formType, $appId)
	{
		// Check model by commcare_app_id
		$model = null;
		$param = ['commcare_app_id'=>$appId];
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
			case FormType::PM_DETAIL :
				$model = ProjectBeneficiary::find()->where($param)->one();
		}
		return $model;
	}

	protected function fileExists(ActiveRecordInterface $model, Attachment $attachment)
	{
		$id = $model->propertyId;
		$file = ActivityFile::find()->where(['activity_id'=>$model->$id])->one();
		return ($file instanceof ActivityFile);
	}
}