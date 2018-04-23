<?php

namespace app\forms;

use SplFileInfo;
use yii\base\BaseObject;

class Attachment extends BaseObject
{
	public $app_id;
	public $content_type;
	public $ext;
	public $filename;
	public $length;
	public $url;

	public function __construct($appId, $object=[])
	{
		$fileInfo = new SplFileInfo($object['url']);
		$this->app_id       = $appId;
		$this->content_type = $object['content_type'];
		$this->ext          = $fileInfo->getExtension();
		$this->filename     = $fileInfo->getFileName();
		$this->length       = $object['length'];
		$this->url          = $object['url'];
	}
}