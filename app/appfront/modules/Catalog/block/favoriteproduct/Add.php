<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\block\favoriteproduct;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Add {
	
	
	public function getLastData(){
		$product_id = Yii::$app->request->get('product_id');
		#û�е�¼���û���ת����¼ҳ��
		if(Yii::$app->user->isGuest){
			$url = Yii::$service->url->getCurrentUrl();
			Yii::$service->customer->setLoginSuccessRedirectUrl($url);
			Yii::$service->url->redirectByUrlKey('customer/account/login');
			exit;
		}
		
		$identity = Yii::$app->user->identity;
		$user_id = $identity->id;
		
		$addStatus = Yii::$service->product->favorite->add($product_id,$user_id);
		if(!$addStatus){
			Yii::$service->page->message->addByHelperErrors();
		}
		$favoriteParam = Yii::$app->getModule('catalog')->params['favorite'];
		# ��ת��
		if(isset($favoriteParam['addSuccessRedirectFavoriteList']) && $favoriteParam['addSuccessRedirectFavoriteList']){
			Yii::$service->url->redirectByUrlKey('customer/productfavorite');
		}else{
			$product 	= Yii::$service->product->getByPrimaryKey($product_id);
			$urlKey 	= $product['url_key'];
			Yii::$service->url->redirectByUrlKey($urlKey);
		}
		
	}
	
	
	
	
	
	
	
	
}
