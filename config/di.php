<?php

\Yii::$container->set('pkpudev\components\behaviors\LocationData', [
	'class'=>'pkpudev\components\behaviors\LocationData',
	'locationModel'=>new \app\models\Location,
	'countryModel'=>new \app\models\Country,
]);