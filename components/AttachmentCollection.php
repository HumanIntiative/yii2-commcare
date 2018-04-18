<?php

namespace app\components;

use app\forms\GeneratorTrait;
use yii\httpclient\Response;

class AttachmentCollection implements \IteratorAggregate, \Countable
{
	use GeneratorTrait;

	protected $data = [];

	public function __construct(Response $response)
	{
		$objects = $response->data['objects'];
		foreach ($objects as $object) {
			$rowId = $object['id'];
			$attachments = $object['attachments'];
			$this->data = array_merge(
				$this->data,
				static::mapAttachments($rowId, $attachments)
			);
		}
	}

	public function getIterator()
	{
		return new \ArrayIterator($this->data);
	}

	public function count()
	{
		return count($this->data);
	}

	public function exists()
	{
		return !empty($this->data);
	}
}