<?php

namespace app\models;

use app\models\base\ProjectBeneficiary as BaseProjectBeneficiary;

/**
 * This is the model class for table "pdg.project__beneficiary".
 */
class ProjectBeneficiary extends BaseProjectBeneficiary
{
	public $comment_approve;
	public $import;

	public function attributeLabels()
	{
	 	return [
			'is_detail' => 'Detail',
			'beneficiary_type_id' => 'Tipe PM',
			'beneficiary_asnaf_id' => 'Tipe Asnaf',
			'pillar_id' => 'Tipe Pilar',
			'beneficiary_id' => 'Penerima Manfaat',
			'status_id' => 'Status',
			'created_name' => 'Dibuat Oleh',
			'comment_approve' => 'Catatan',
		];
	}

	public function beforeSave($insert)
	{
		if (!parent::beforeSave($insert)) return false;
		if ($this->isNewRecord) {
			$this->status_id = 'New'; // change later
			$this->created_by = null;
			$this->created_name = null;
			$this->created_stamp = date('Y-m-d H:i:s');
		}
		return true;
	}

	//
	// Relations
	//

	public function getProject()
	{
		return $this->hasOne(Project::className(), ['id' => 'project_id']);
	}

	public function getBeneficiaryType()
	{
		return $this->hasOne(BeneficiaryType::className(), ['id' => 'beneficiary_type_id']);
	}

	public function getBeneficiary()
	{
		return $this->hasOne(Beneficiary::className(), ['id' => 'beneficiary_id']);
	}
}
