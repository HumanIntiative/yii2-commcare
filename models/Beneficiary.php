<?php

namespace app\models;

use app\models\base\Beneficiary as BaseBeneficiary;
use pkpudev\components\behaviors\LocationData;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "beneficiary.beneficiary".
 */
class Beneficiary extends BaseBeneficiary
{
	const GENDER_MALE = 'L';
	const GENDER_FEMALE = 'P';

	public $location_id_1;
	public $location_id_2;
	public $location_id_3;
	public $location_id_4;

	public static function genderTypes()
	{
		return ['L'=>'Laki-laki', 'P'=>'Perempuan'];
	}

	/**
	 * @return array list of attribute names.
	 */
	public function listAttributes()
	{
		return [
			'id',
			'beneficiary_no',
			'beneficiary_type_id',
			'full_name',
			'gender',
			'marital_status', // not null
			'religion', // not null
			'nationality', // not null
			'birth_place',
			'birth_date',
			'address',
			'job', // not null
			'location_id',
			'location_id_1',
			'location_id_2',
			'location_id_3',
			'location_id_4',
		];
	}

	public function attributeLabels()
	{
	 	return ArrayHelper::merge(parent::attributeLabels(), [
			'beneficiary_type_id' => 'Tipe PM',
			'beneficiary_no' => 'No PM',
		]);
	}

	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'location' => LocationData::className(),
		]);
	}

	public function beforeValidate()
	{
		if (!parent::beforeValidate()) return false;

		$user = \Yii::$app->user;
		if ($this->isNewRecord) {
			// $this->beneficiary_no = $this->generateNo();
			$this->branch_id = 1;
			$this->company_id = 1;
			$this->created_stamp = date('Y-m-d H:i:s');

			// Begin These attributes need improvement
			$this->marital_status = 'Status';
			$this->religion = 0;
			$this->nationality = 'WN';
			$this->job = 'Job';
			// End
		}
		return true;
	}

	public function getGenderText()
	{
		$types = static::genderTypes();
		return $types[$this->gender];
	}
}
