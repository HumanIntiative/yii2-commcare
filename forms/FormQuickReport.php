<?php

namespace app\forms;

use yii\base\BaseObject;

class FormQuickReport extends BaseObject
{
	use GeneratorTrait;

	public $id; //guid
	public $pmp;
	public $judul;
	public $tgl_pelaporan;
	public $alamat;
	public $aktifitas;
	public $attachments;

	/**
	 * @param array $result From Response -> data['objects']
	 * 
	 * @return FormQuickReport
	 */
	public static function create($result=[])
	{
		$fields = ['aktifitas', 'alamat', 'judul', 'pmp', 'tgl_pelaporan'];
		return static::generate($fields, $result);
	}
}