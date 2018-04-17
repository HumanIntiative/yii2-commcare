<?php

namespace app\forms;

use SplFileInfo;
use yii\base\BaseObject;

class Attachment extends BaseObject
{
	public $content_type;
	public $filename;
	public $length;
	public $url;

	public function __construct($object=[])
	{
		$fileInfo = new SplFileInfo($object['url']);
		$this->content_type = $object['content_type'];
		$this->filename = $fileInfo->getFileName();
		$this->length = $object['length'];
		$this->url = $object['url'];
	}

	public function download()
	{
		// 
	}
}