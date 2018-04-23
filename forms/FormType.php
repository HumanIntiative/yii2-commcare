<?php

namespace app\forms;

use pkpudev\fasil\models\Activity;

class FormType
{
	const QUICK_REPORT = 'Quick Report';
	const BERITA_AKTIFITAS = 'Berita Aktifitas';
	const CERITA_HUMANIS = 'Cerita Humanis';
	const PM_DETAIL = 'Penerima Manfaat';
	const PM_AGGREGATE = 'Penerima Manfaat (Agregat)';

	/**
	 * @return bool
	 */
	public static function exists($formType)
	{
		return in_array($formType, [
			static::QUICK_REPORT,
			static::BERITA_AKTIFITAS,
			static::CERITA_HUMANIS,
			static::PM_DETAIL,
			static::PM_AGGREGATE,
		]);
	}

	/**
	 * @return bool
	 */
	public static function getActivityType($formType)
	{
		if (!static::exists($formType)) return null;
		return static::getActivityMap()[$formType];
	}

	/**
	 * @return Array
	 */
	protected static function getActivityMap()
	{
		return [
			static::QUICK_REPORT => Activity::QUICK,
			static::BERITA_AKTIFITAS => Activity::NEWS,
			static::CERITA_HUMANIS => Activity::STORY,
			static::PM_DETAIL => Activity::PM,
			// static::PM_AGGREGATE => Activity::PM,
		];
	}
}