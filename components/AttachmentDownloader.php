<?php

namespace app\components;

use app\forms\Attachment;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;

class AttachmentDownloader
{
	private $attachment;
	private $authHeader;

	/**
	 * @param array $authHeader
	 * @param Attachment $attachment
	 */
	public function __construct($authHeader, Attachment $attachment)
	{
		if (!isset($authHeader['Authorization'])) {
			throw new InvalidConfigException("Unknown Auth Header!");
		}

		$this->attachment = $attachment;
		$this->authHeader = $authHeader;
	}

	public function download()
	{
		$headers = array_merge($this->authHeader, [
			'content-type' => $this->attachment->content_type,
		]);

		$download = (new Client)->createRequest()
			->setUrl($this->attachment->url)
			->addHeaders($headers)
			->send();

		return $download->content;
	}
}