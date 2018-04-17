<?php

namespace app\commands\actions;

use app\components\AttachmentDownloader;
use app\components\FormCollection;
use app\forms\FormQuickReport;
use app\forms\FormType;
use pkpudev\fasil\models\QuickReport;
use yii\base\Action;

class QuickReportAction extends Action
{
	public function run()
	{
		$collection = new FormCollection($this->controller->response, FormType::QUICK_REPORT);

		$models = [];
		foreach ($collection as $form) {
			// Create Model
			$model = $this->createModel($form);
			// Add to Array
			array_push($models, $model);

			/*if (is_array($form->attachments)) {
				foreach ($form->attachments as $name => $attachment) {
					$file = new AttachmentDownloader($this->controller->commcare->authHeader, $attachment);
					array_push($files, $file);
				}
			}*/
		}

		// Foreach models
		// 	Save model 	

		var_dump($model);
	}

	protected function createModel(FormQuickReport $form)
	{
		// Check QR by app_id
		// Check PMP
		$param = ['commcare_app_id'=>$form->id];
		$model = QuickReport::find()->where($param)->one();
		if (!$model) {
			$model = new QuickReport;
			$model->commcare_app_id = $form->id;
		}
		$model->report_name = $form->judul;
		$model->report_date = $form->tgl_pelaporan;
		$model->address = $form->alamat;
		$model->content = $form->aktifitas;

		return $model;
	}
}