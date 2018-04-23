<?php

namespace app\models;

use Yii;
use app\models\base\File as BaseFile;

/**
 * This is the model class for table "pdg.file".
 */
class File extends BaseFile
{
	protected $_attrs;

	public function getFilepath()
	{
		$path = Yii::$app->fs->path;
		return "{$path}/{$location}";
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert)
	{
		if (!parent::beforeSave($insert)) return false;
		if (!$this->isNewRecord) return true;

		$this->created_by = Yii::$app->user->id;
		$this->created_stamp = new \yii\db\Expression('NOW()');

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function beforeDelete()
	{
		$this->_attrs = $this->attributes;
		return parent::beforeDelete();
	}

	/**
	 * @inheritdoc
	 */
	public function afterDelete()
	{
		$webroot = Yii::getAlias('@webroot');
		$fileName = $this->_attrs['file_name'];
		$fileLocation = $webroot.$this->_attrs['location'];

		if (file_exists($fileLocation)) {
			@unlink($fileLocation);
		}
	}
}
