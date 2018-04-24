<?php

namespace app\components;

use app\forms\GeneratorTrait;
use yii\httpclient\Response;

class AttachmentCollection implements \IteratorAggregate, \Countable
{
	use CollectionTrait, GeneratorTrait;

	protected $response;
	protected $formType;
	protected $data = [];

	public function __construct(Response $response, $formType=null)
	{
		$this->response = $response;
		$this->formType = $formType;

		$objects = $this->mapData();
		foreach ($objects as $object) {
			$rowId = $object['id'];
			$pmp = $object['form']['pmp'];
			$attachments = $object['attachments'];
			$this->data = array_merge(
				$this->data,
				static::mapAttachments($rowId, $pmp, $attachments)
			);
		}
	}

	protected function mapData()
	{
		if (null === $this->formType) {
			return $this->response->data['objects'];
		} else {
			return $this->filterData($this->response, $this->formType);
		}
	}
}