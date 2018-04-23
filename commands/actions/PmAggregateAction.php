<?php

namespace app\commands\actions;

use app\components\FormCollection;
use app\forms\FormPmAggregate;
use app\forms\FormType;
use pkpudev\fasil\models\BeneficiaryAggregate;
use yii\base\Action;

class PmAggregateAction extends Action
{
	use ActionTrait;

	public function run()
	{
		// Get Form Collection
		$collection = new FormCollection($this->controller->response, FormType::PM_AGGREGATE);
		// Generate models
		$models = $this->generateModels($collection);
		// Insert Data
		$count = $this->insertData($models);
	}

	public function getAttributeList()
	{
		return [
			'jml_orang_tua', 'jml_dewasa', 'jml_remaja', 'jml_anak_anak', 'jml_balita',
			'jml_ibu_hamil', 'jml_ibu_menyusui', 'jml_difabel', 'jml_fakir_miskin', 'jml_muallaf',
			'jml_riqab', 'jml_gharim', 'jml_fiisabilillah', 'jml_ibnu_sabil',
		];
	}

	public function createModel(FormPmAggregate $form)
	{
		// Check QR by app_id
		$param = ['commcare_app_id'=>$form->id];
		$model = BeneficiaryAggregate::find()->where($param)->one();
		// Check PMP
		$projectId = $this->findProjectId($form->pmp);
		if (null === $model && $projectId) {
			$model = new BeneficiaryAggregate;
			$model->commcare_app_id = $form->id;
			$model->project_id = $projectId;
			foreach ($this->getAttributeList() as $attribute) {
				$model->$attribute = $form->$attribute;
			}
			return $model;
		}
		return null;
	}
}