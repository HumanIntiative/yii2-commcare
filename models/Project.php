<?php

namespace app\models;

use app\models\base\Project as BaseProject;
use pkpudev\components\behaviors\LocationData;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pdg.project".
 */
class Project extends BaseProject
{
	const PM_AGGREGATE = 'A';
	const PM_DETAIL = 'D';

	public $comment;
	public $location_id_1;
	public $location_id_2;
	public $location_id_3;
	public $location_id_4;

	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'location' => LocationData::className(),
		]);
	}

	public function getText()
	{
		return "<strong>{$this->project_no}</strong> &mdash; " . $this->project_name;
	}
}
