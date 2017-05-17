<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Payment\controllers\paypal;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ExpressController extends AppfrontController
{
    public $enableCsrfValidation = true;
	
	public function actionStart(){
		$data = $this->getBlock()->startExpress();
		
	}
	# 2.Review  从paypal确认后返回
	public function actionReview(){
		$_csrf = Yii::$app->request->post('_csrf');
		if($_csrf){
			$status = $this->getBlock('placeorder')->getLastData();
			if(!$status){
				//var_dump(Yii::$service->helper->errors->get());
				//exit;
			}
		}
		$data = $this->getBlock()->getLastData();
		if(is_array($data) && !empty($data) ){	
			return $this->render($this->action->id,$data);
		}else{
			return $data;
		}		
	
	}
	
	
	
}
















