<?php

namespace app\components\uploader;

use yii\db\ActiveRecordInterface;
use yii\httpclient\Response;

class Config
{
	/**
	 * @var string $activityType QuickReport etc
	 */
	public $activityType;
	/**
	 * @var Attachment $model
	 */
	public $attachment;
	/**
	 * @var SftpFilesystem $fileSystem
	 */
	public $fileSystem;
	/**
	 * @var ActiveRecordInterface $model
	 */
	public $model;
	/**
	 * @var string $prefix
	 */
	public $prefix;
	/**
	 * @var Response $response
	 */
	public $response;
	/**
	 * @var string $targetDir
	 */
	public $targetDir = 'fasil_attachment';
}