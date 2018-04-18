<?php

namespace app\models;

use pkpudev\components\behaviors\LocationData;
use pkpudev\fasil\models\QuickReport as BaseQuickReport;
use yii\helpers\ArrayHelper;

class QuickReport extends BaseQuickReport
{
	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'location'=>LocationData::className(),
		]);
	}
}