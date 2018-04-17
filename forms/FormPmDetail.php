<?php

namespace app\forms;

use yii\base\BaseObject;

class FormPmDetail extends BaseObject
{
	use GeneratorTrait;

	public $id; //guid
	public $pmp;
	public $nama;
	public $nik;
	public $jenis_kelamin;
	public $tempat_lahir;
	public $tanggal_lahir;
	public $alamat;
	public $tipe_pm;
	public $tipe_pilar;
	public $asnaf;
	public $attachments;

	/**
	 * @param array $result From Response -> data['objects']
	 * 
	 * @return FormPmDetail
	 */
	public static function create($result=[])
	{
		$fields = [
			'pmp', 'nama', 'nik', 'jenis_kelamin', 'tempat_lahir',
			'tanggal_lahir', 'alamat', 'tipe_pm', 'tipe_pilar', 'asnaf'
		];
		return static::generate($fields, $result);
	}
}