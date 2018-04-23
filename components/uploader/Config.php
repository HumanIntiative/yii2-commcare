<?php

namespace app\components\uploader;

use yii\db\ActiveRecordInterface;
use yii\httpclient\Response;

class Config
{
	/**
	 * @var ActiveRecordInterface $model
	 */
	public $model;
	/**
	 * @var Attachment $model
	 */
	public $attachment;
	/**
	 * @var Response $response
	 */
	public $response;
	/**
	 * @var string $prefix
	 */
	public $prefix;
	/**
	 * @var string $targetDir
	 */
	public $targetDir;
	/**
	 * @var SftpFilesystem $fileSystem
	 */
	public $fileSystem;
}