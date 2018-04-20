<?php

namespace app\commands\actions;

use app\components\AttachmentCollection;
use app\components\AttachmentDownloader;
use app\forms\FormType;
use yii\base\Action;

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
		$collection = new AttachmentCollection($this->response, FormType::QUICK_REPORT);

		// Each Attachment
		foreach ($collection as $name=>$attachment) {
			// Download an Attachment
			$downloader = new AttachmentDownloader($this->authHeader, $attachment);
			// Save to a folder using SCP
			
			// Save to File, ActivityFile
		}
	}
}