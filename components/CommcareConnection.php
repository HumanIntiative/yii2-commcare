<?php

namespace app\components;

use yii\base\Component;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\Response;

class CommcareConnection extends Component
{
	/**
	 * @var APP_ID Mulia Project Report
	 */
	const APP_ID = '56d4bdd382ca10bd5eb7a4c1d8a628c3';
	const FORM_URL = 'form';

	private $headers;
	private $client;
	private $sourceUrl;

	public function __construct($sourceUrl=null)
	{
		$server = getenv('COM_SERVER');
		$apiUrl = sprintf('/a/%s/api/%s', getenv('COM_DOMAIN'), getenv('COM_APIVER'));
		$authorization = sprintf('ApiKey %s:%s', getenv('COM_USERNAME'), getenv('COM_APIKEY'));

		$this->client = new Client(['baseUrl'=>$server.$apiUrl]);
		$this->headers = ['Authorization'=>$authorization];
		$this->sourceUrl = $sourceUrl ?: self::FORM_URL;
	}

	/**
	 * @return Request httpclient request
	 */
	public function getRequest()
	{
		return $this->client
			->createRequest()
			->setHeaders($this->headers)
			->setMethod('GET')
			->setData(['app_id'=>self::APP_ID,'limit'=>100])
			->setUrl($this->sourceUrl);
	}

	/**
	 * @return Response httpclient response
	 */
	public function getResponse()
	{
		return $this->request->send();
	}

	/**
	 * @return array Auth headers
	 */
	public function getAuthHeader()
	{
		return $this->headers;
	}
}