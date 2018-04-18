<?php

namespace app\commands\actions;

use app\components\FormCollection;
use app\forms\FormCeritaHumanis;
use app\forms\FormType;
use pkpudev\fasil\models\Story;
use yii\base\Action;

class CeritaAction extends Action
{
	use ActionTrait;

	public function run()
	{
		// Get Form Collection
		$collection = new FormCollection($this->controller->response, FormType::CERITA_HUMANIS);
		// Generate models
		$models = $this->generateModels($collection);
		// Insert Data
		$count = $this->insertData($models);
	}

	public function createModel(FormCeritaHumanis $form)
	{
		// Check QR by app_id
		$param = ['commcare_app_id'=>$form->id];
		$model = Story::find()->where($param)->one();
		// Check PMP
		$projectId = $this->findProjectId($form->pmp);
		if (null === $model && $projectId) {
			$model = new Story;
			$model->commcare_app_id = $form->id;
			$model->project_id = $projectId;
			$model->title = $form->judul;
			$model->post_date = $form->tanggal;
			$model->content = $form->konten;
			return $model;
		}
		return null;
	}
}