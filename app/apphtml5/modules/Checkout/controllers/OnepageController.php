<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\apphtml5\modules\Checkout\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\apphtml5\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class OnepageController extends AppfrontController
{
    public $enableCsrfValidation = true;
	
	//public function init(){
	//	Yii::$service->page->theme->layoutFile = 'one_step_checkout.php';
		
	//}
	
	public function actionIndex(){
		//var_dump(Yii::$app->request->post());
		
		$_csrf = Yii::$app->request->post('_csrf');
		if($_csrf){
			$status = $this->getBlock('placeorder')->getLastData();
			if(!$status){
				//var_dump(Yii::$service->helper->errors->get());
				//exit;
			}
		}
		
		
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
		
	}
	
	
	public function actionChangecountry(){
		$this->getBlock('index')->ajaxChangecountry();
		
	}
	
	public function actionAjaxupdateorder(){
		$this->getBlock('index')->ajaxUpdateOrderAndShipping();
		
	}
	
}
















