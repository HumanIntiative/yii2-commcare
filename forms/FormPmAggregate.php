<?php

namespace app\forms;

use yii\base\BaseObject;

class FormPmAggregate extends BaseObject
{
	use GeneratorTrait;

	public $id; //guid
	public $pmp;
	public $jml_orang_tua;
	public $jml_dewasa;
	public $jml_remaja;
	public $jml_anak_anak;
	public $jml_balita;
	public $jml_ibu_hamil;
	public $jml_ibu_menyusui;
	public $jml_difabel;
	public $jml_fakir_miskin;
	public $jml_muallaf;
	public $jml_riqab;
	public $jml_gharim;
	public $jml_fiisabilillah;
	public $jml_ibnu_sabil;
	public $attachments;

	/**
	 * @param array $result From Response -> data['objects']
	 * 
	 * @return FormPmAggregate
	 */
	public static function create($result=[])
	{
		$fields = [
			'pmp','jml_orang_tua','jml_dewasa','jml_remaja',
			'jml_balita','jml_ibu_hamil','jml_ibu_menyusui',
			'jml_difabel','jml_fakir_miskin','jml_muallaf','jml_riqab',
			'jml_gharim','jml_fiisabilillah','jml_ibnu_sabil'
		];
		$form = static::generate($fields, $result);
		$form->jml_anak_anak = $result['form']['jml_anak-anak'];
		return $form;
	}
}