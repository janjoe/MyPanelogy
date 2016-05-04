<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomErrorController extends CErrorHandler {
	
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error) {
	        // $this->render('error', $error);

	        Airbrake\Instance::notify($error);
	    }
	}
}