<?php

namespace app\commands\actions;

use app\components\FormCollection;
use app\models\Project;

trait ActionTrait
{
	public function generateModels(FormCollection $collection)
	{
		echo '--- Generating data ...'."\r\n";
		$models = [];
		foreach ($collection as $form) {
			// Create Model
			$model = $this->createModel($form);
			// Add to Array
			if ($model) {
				array_push($models, $model);
			}
		}
		echo '--- Finish generating data : '.$count."\r\n";

		return $models;
	}

	public function insertData($models)
	{
		echo '--- Insert to database ...'."\r\n"; $count = 0;
		try {
			foreach ($models as $row => $model) {
				if ($model->save()) $count++;
			}
		} catch (\Exception $e) {
			throw $e;
		}
		echo '--- Finish insert data : '.$count."\r\n";

		return $count;
	}

	public function findProjectId($projectNo)
	{
		$project = Project::find()
			->where(['project_no'=>$projectNo])
			->one();
		if ($project instanceof Project) {
			return $project->id;
		}
		return null;
	}
}