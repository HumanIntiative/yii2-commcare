<?php

namespace app\commands\actions;

use app\components\AttachmentDownloader;
use app\components\FormCollection;
use app\forms\FormQuickReport;
use app\forms\FormType;
use app\models\QuickReport;
use yii\base\Action;

class QuickReportAction extends Action
{
	use ActionTrait;

	public function run()
	{
		// Get Form Collection
		$collection = new FormCollection($this->controller->response, FormType::QUICK_REPORT);
		// Generate models
		$models = $this->generateModels($collection);
		// Insert Data
		$count = $this->insertData($models);
	}

	public function createModel(FormQuickReport $form)
	{
		// Check QR by app_id
		// Check PMP
		$param = ['commcare_app_id'=>$form->id];
		$model = QuickReport::find()->where($param)->one();
		$projectId = $this->findProjectId($form->pmp);
		if (null === $model && $projectId) {
			$model = new QuickReport;
			$model->commcare_app_id = $form->id;
			$model->project_id = $projectId;
			$model->report_name = $form->judul;
			$model->report_date = $form->tgl_pelaporan;
			$model->address = $form->alamat;
			$model->content = $form->aktifitas;
			return $model;
		}
		return null;
	}
}