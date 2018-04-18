<?php

namespace app\commands\actions;

use app\components\FormCollection;
use app\forms\FormPmDetail;
use app\forms\FormType;
use app\models\Beneficiary;
use pkpudev\fasil\models\ProjectBeneficiary;
use yii\base\Action;

class PmDetailAction extends Action
{
	use ActionTrait;

	public function run()
	{
		// Get Form Collection
		$collection = new FormCollection($this->controller->response, FormType::PM_DETAIL);
		// Generate models
		$models = $this->generateModels($collection);
		// Insert Data
		$count = $this->insertData($models);
	}

	public function createModel(FormPmDetail $form)
	{
		// Check QR by app_id
		$param = ['commcare_app_id'=>$form->id];
		$model = ProjectBeneficiary::find()->where($param)->one();
		// Check PMP
		$projectId = $this->findProjectId($form->pmp);
		// Check benef by NIK
		$condition = ['or', ['ktp_no'=>$form->nik], ['kk_no'=>$form->nik]];
		$benef = Beneficiary::find()->where($condition)->one();

		if ((null === $model) && $projectId) {
			if (null === $benef) {
				$benef = new Beneficiary;
				$benef->beneficiary_type_id = $form->tipe_pm;
				$benef->full_name = $form->nama; // nickname
				$benef->gender = $form->jenis_kelamin; // L|P
				$benef->birth_place = $form->tempat_lahir;
				$benef->birth_date = $form->tanggal_lahir;
				$benef->address = $form->alamat;
				$benef->save(false);
			}

			$model = new ProjectBeneficiary;
			$model->commcare_app_id = $form->id;
			$model->project_id = $projectId;
			$model->beneficiary_id = $benef->id;
			$model->beneficiary_asnaf_id = $form->asnaf;
			$model->beneficiary_type_id = $form->tipe_pm;
			$model->is_detail = 1;
			$model->pillar_id = $form->tipe_pilar;
			return $model;
		}
		return null;
	}
}