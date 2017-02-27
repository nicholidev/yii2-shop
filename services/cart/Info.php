<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\cart;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use fecshop\models\mysqldb\Cart\Item as MyCartItem;
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Info extends Service
{
	# 上架状态产品加入购物车时，
	# 如果addToCartCheckSkuQty设置为true，则需要检查产品qty是否>购买qty，
	# 如果设置为false，则不需要，也就是说产品库存qty小于购买qty，也是可以加入购物车的。
	public $addToCartCheckSkuQty = true;
	
	/**
	 * @property $item | Array  , example 
	 * $item = [
	 *		'product_id' 		=> 22222,
	 *		'custom_option_sku' => ['color'=>'red','size'=>'l'],
	 *		'qty' 				=> 22,
	 * ];
	 * @proeprty $product | Object , Product Model
	 * return boolean 是否满足条件
	 * 在产品加入购物车之前，检查产品是否存在，产品的状态，库存状态等
	 * 满足条件返回true
	 */
	public  function checkProductBeforeAdd($item,$product){
		$qty 				= $item['qty'];
		$product_id 		= $item['product_id'];
		$custom_option_sku  = $item['custom_option_sku'];
		# 验证提交产品数据
		# 验证产品是否存在
		if(!$product['sku']){
			Yii::$service->helper->errors->add('this product is not exist');
			return false;
		}
		# 验证库存 是否库存满足？
		if($this->addToCartCheckSkuQty){
			# 验证：1.上架状态， 2.库存个数是否大于购买个数
			# 该验证方式是默认验证方式
			if(!Yii::$service->product->stock->productIsInStock($product,$qty ,$custom_option_sku)){
				Yii::$service->helper->errors->add('product is Stock Out');
				
				return false;
			}
		}else{
			# 验证：1.上架状态
			if(!Yii::$service->product->stock->checkOnShelfStatus($product['is_in_stock'])){
				Yii::$service->helper->errors->add('product is Stock Out');
			
				return false;
			}
		}
		
		
		# 验证产品是否
		if($product['status'] != 1){
			Yii::$service->helper->errors->add('product is not active');
			return false;
		}
		return true;
	}
	
	public function getCustomOptionSku($item,$product){
		$qty 				= $item['qty'];
		$custom_option_sku 	= $item['custom_option_sku'];
		$product_id 		= $item['product_id'];
		
		$co_sku = '';
		if($custom_option_sku){
			$product_custom_option = $product['custom_option'];
			$co_sku = Yii::$service->product->info->getProductCOSku($custom_option_sku,$product_custom_option);
			
			if($co_sku){
				return $co_sku;
			}
		}
		
		
	}
	
	
	
	
	
	
	
}