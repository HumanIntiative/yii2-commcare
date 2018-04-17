<?php

$mongodsn = sprintf('mongodb://%s:%s@%s:%s/%s',
	getenv('MONGO_USER'),
	getenv('MONGO_PASS'),
	getenv('MONGO_HOST'),
	getenv('MONGO_PORT'),
	getenv('MONGO_DB'));

return [
	'id' => 'commcare-api',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'app\commands',
	'components' => [
		'cache' => [
			'class' => 'yii\caching\DummyCache',
		],
		'mongodb' => [
      'class' => 'yii\mongodb\Connection',
      'dsn' => $mongodsn,
		],
		'request' => null,
	],
	'aliases' => [],
];