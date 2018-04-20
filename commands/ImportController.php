<?php

namespace app\commands;

use app\components\AttachmentCollection;
use app\components\CommcareConnection;
use app\components\FormCollection;
use app\forms\FormType;
use yii\console\Controller;

class ImportController extends Controller
{
	public $commcare;
	public $response;

	public function init()
	{
		parent::init();
		$this->commcare = new CommcareConnection('form');
		$this->response = $this->commcare->response;
	}

	public function actions()
	{
		return [
			'quick'=>actions\QuickReportAction::className(),
			'berita'=>actions\BeritaAction::className(),
			'cerita'=>actions\CeritaAction::className(),
			'pm-detail'=>actions\PmDetailAction::className(),
			'attachment'=>actions\AttachmentAction::className(),
		];
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

	public function actionPmAggregate()
	{
		$aggregates = new FormCollection($this->response, FormType::PM_AGGREGATE);
		var_dump($aggregates);
	}
}