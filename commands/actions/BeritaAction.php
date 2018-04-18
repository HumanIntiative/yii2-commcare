<?php

namespace app\commands\actions;

use app\components\FormCollection;
use app\forms\FormBeritaAktifitas;
use app\forms\FormType;
use pkpudev\fasil\models\News;
use yii\base\Action;

class BeritaAction extends Action
{
	use ActionTrait;

	public function run()
	{
		// Get Form Collection
		$collection = new FormCollection($this->controller->response, FormType::BERITA_AKTIFITAS);
		// Generate models
		$models = $this->generateModels($collection);
		// Insert Data
		$count = $this->insertData($models);
	}

	public function createModel(FormBeritaAktifitas $form)
	{
		// Check QR by app_id
		$param = ['commcare_app_id'=>$form->id];
		$model = News::find()->where($param)->one();
		// Check PMP
		$projectId = $this->findProjectId($form->pmp);
		if (null === $model && $projectId) {
			$model = new News;
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