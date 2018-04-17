<?php

namespace app\forms;

use yii\base\BaseObject;

class FormCeritaHumanis extends BaseObject
{
	use GeneratorTrait;

	public $id; //guid
	public $pmp;
	public $tanggal;
	public $judul;
	public $konten;
	public $attachments;

	/**
	 * @param array $result From Response -> data['objects']
	 * 
	 * @return FormCeritaHumanis
	 */
	public static function create($result=[])
	{
		$fields = ['pmp', 'tanggal', 'judul', 'konten'];
		return static::generate($fields, $result);
	}
}