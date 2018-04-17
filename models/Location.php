<?php

namespace app\models;

use app\models\base\Location as BaseLocation;

/**
 * This is the model class for table "com_location".
 */
class Location extends BaseLocation
{
	public function fields()
	{
		return [
			'id',
			'text'=>'location_name',
		];
	}
}