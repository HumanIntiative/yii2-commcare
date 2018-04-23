<?php

$postgresdsn = sprintf('pgsql:host=%s;port=%s;dbname=%s',
	getenv('DB_HOST'),
	getenv('DB_PORT'),
	getenv('DB_NAME'));

$mongodsn = sprintf('mongodb://%s:%s@%s:%s/%s',
	getenv('MONGO_USER'),
	getenv('MONGO_PASS'),
	getenv('MONGO_HOST'),
	getenv('MONGO_PORT'),
	getenv('MONGO_DB'));

require('di.php'); // Initialize DI

return [
	'id' => 'commcare-api',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'app\commands',
	'components' => [
		'cache' => [
			'class' => 'yii\caching\DummyCache',
		],
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => $postgresdsn,
			'username' => getenv('DB_USER'),
			'password' => getenv('DB_PASS'),
			'charset' => 'utf8',
		],
		'log' => [
			'traceLevel' => 3, //0
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
					'categories' => [
						'yii\db\*',
					],
				],
			],
		],
		'mongodb' => [
			'class' => 'yii\mongodb\Connection',
			'dsn' => $mongodsn,
		],
		'request' => null,
		'sftp' => [
			'class' => 'creocoder\flysystem\SftpFilesystem',
			'host' => getenv('SFTP_HOST'),
			'username' => getenv('SFTP_USER'),
			'password' => getenv('SFTP_PASS'),
			'root' => getenv('SFTP_ROOT'),
		],
	],
	'aliases' => [],
];