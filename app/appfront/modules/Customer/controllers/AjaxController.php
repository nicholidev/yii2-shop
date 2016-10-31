<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Customer\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
use fecshop\app\appfront\helper\test\My;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AjaxController extends AppfrontController
{
   
	/**
	 * ajax 请求 ，得到是否登录账户的信息
	 */
	public function actionIndex(){
		$result_arr = [];
		if(Yii::$app->request->isAjax){
			$result_arr['loginStatus'] 	= false;
			$result_arr['favorite'] 	= false;
			if(!Yii::$app->user->isGuest){
				$result_arr['loginStatus'] = true;
				$product_id = Yii::$app->request->get('product_id');
				if($product_id){
					$favorite = Yii::$service->product->favorite->getByProductIdAndUserId($product_id);
					$favorite ? ($result_arr['favorite'] = true) : '';
				}
			}
		}
		echo json_encode($result_arr);
		exit;
	}
	
}
















