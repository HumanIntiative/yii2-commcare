<?php

namespace app\commands;

use app\components\AttachmentDownloader;
use app\components\CommcareConnection;
use app\components\FormCollection;
use app\forms\FormType;
use yii\console\Controller;

class ImportController extends Controller
{
	private $commcare;
	private $response;

	public function init()
	{
		parent::init();
		$this->commcare = new CommcareConnection('form');
		$this->response = $this->commcare->response;
	}

	public function actionCount()
	{
		$fnCount = function($formType) {
			$collection = new FormCollection($this->response, $formType);
			return $collection->count();
		};

		var_dump([
			['type'=>'QR', 'total'=>$fnCount(FormType::QUICK_REPORT)],
			['type'=>'BA', 'total'=>$fnCount(FormType::BERITA_AKTIFITAS)],
			['type'=>'CH', 'total'=>$fnCount(FormType::CERITA_HUMANIS)],
			['type'=>'PMD', 'total'=>$fnCount(FormType::PM_DETAIL)],
			['type'=>'PMA', 'total'=>$fnCount(FormType::PM_AGGREGATE)],
		]);
	}

	public function actionQuick()
	{
		$collection = new FormCollection($this->response, FormType::QUICK_REPORT);

		$files = [];
		foreach ($collection as $form) {
			if (is_array($form->attachments)) {
				foreach ($form->attachments as $name => $attachment) {
					$file = new AttachmentDownloader($this->commcare->authHeader, $attachment);
					array_push($files, $file);
				}
			}
		}

		var_dump($files);
	}

	public function actionBerita()
	{
		$beritas = new FormCollection($this->response, FormType::BERITA_AKTIFITAS);
		var_dump($beritas);
	}

	public function actionCerita()
	{
		$ceritas = new FormCollection($this->response, FormType::CERITA_HUMANIS);
		var_dump($ceritas);
	}

	public function actionPmDetail()
	{
		$details = new FormCollection($this->response, FormType::PM_DETAIL);
		var_dump($details);
	}

	public function actionPmAggregate()
	{
		$aggregates = new FormCollection($this->response, FormType::PM_AGGREGATE);
		var_dump($aggregates);
	}
}