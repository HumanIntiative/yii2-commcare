<?php

namespace app\components;

use app\forms\FormBeritaAktifitas;
use app\forms\FormCeritaHumanis;
use app\forms\FormPmAggregate;
use app\forms\FormPmDetail;
use app\forms\FormQuickReport;
use app\forms\FormType;
use yii\base\Component;
use yii\httpclient\Response;

class FormCollection extends Component
{
	private $data;

	public function __construct(Response $response, $formType)
	{
		$filteredData = $this->filterData($response, $formType);
		$this->data = array_map(function($row) use ($formType) {
			return $this->createForm($formType, $row);
		}, $filteredData);
	}

	public function getForms()
	{
		return $this->data;
	}

	public function getCount()
	{
		return count($this->data);
	}

	protected function createForm($formType, $row)
	{
		$form = null;
		switch ($formType) {
			case FormType::QUICK_REPORT :
				$form = FormQuickReport::create($row);
				break;
			case FormType::BERITA_AKTIFITAS :
				$form = FormBeritaAktifitas::create($row);
				break;
			case FormType::CERITA_HUMANIS :
				$form = FormCeritaHumanis::create($row);
				break;
			case FormType::PM_DETAIL :
				$form = FormPmDetail::create($row);
				break;
			case FormType::PM_AGGREGATE :
				$form = FormPmAggregate::create($row);
				break;
		}
		return $form;
	}

	protected function filterData(Response $response, $formType)
	{
		return array_filter(
			$response->data['objects'],
			function($row) use ($formType) {
				return $row['form']['@name'] == $formType;
			}
		);
	}
} 