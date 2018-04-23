<?php

namespace app\components;

class GlobalHelper
{
	public static function shortClassname($object)
	{
		if ($reflect = new \ReflectionClass($object)) {
			return $reflect->getShortName();
		}
		return null;
	}

	public static function isImageFile($mime)
	{
		return in_array($mime, [
			'image/gif', 'image/png', 'image/jpeg', 'image/bmp', 'image/webp'
		]);
	}
}