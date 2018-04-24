<?php

namespace app\commands;

use yii\console\Controller as BaseController;

class Controller extends BaseController
{
	/**
	 * @var string Date Start
	 */
	public $start;
	/**
	 * @var string Date End
	 */
	public $end;
  
  /**
	 * @inheritdoc
	 */
  public function options($actionID)
  {
    return ['start', 'end'];
  }
  /**
	 * @inheritdoc
	 */
  public function optionAliases()
  {
    return ['s'=>'start', 'e'=>'end'];
  }
}