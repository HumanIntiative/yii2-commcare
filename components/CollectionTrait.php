<?php

namespace app\components;

use yii\httpclient\Response;

trait CollectionTrait
{
	protected function filterData(Response $response, $formType)
	{
		return array_filter(
			$response->data['objects'],
			function($row) use ($formType) {
				return $row['form']['@name'] == $formType;
			}
		);
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