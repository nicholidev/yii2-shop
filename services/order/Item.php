<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\order;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use fec\helpers\CSession;
use fecshop\models\mysqldb\Order as MyOrder;
use fecshop\models\mysqldb\order\Item as MyOrderItem;
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Item extends Service
{
	/**
	 * @property $order_id | Int 
	 * @return Array
	 * ͨ��order_id �õ����е�items
	 */
	protected function actionGetByOrderId($order_id){
		$items = MyOrderItem::find()->asArray()->where([
			'order_id' => $order_id,
		])->all();
		foreach($items as $k=>$one){
			$product_id = $one['product_id'];
			$product_one = Yii::$service->product->getByPrimaryKey($product_id);
				
			$productSpuOptions = $this->getProductSpuOptions($product_one);
			//var_dump($productSpuOptions);
			$items[$k]['spu_options'] = $productSpuOptions;
			$items[$k]['custom_option']	= $product_one['custom_option'];
			$items[$k]['custom_option_info'] = $this->getProductOptions($items[$k]);
			$items[$k]['image'] = $this->getProductImage($product_one,$one);	
		
		}
		return $items ;
	}
	/**
	 * @property $product_one | Object, product model
	 * @property $item_one | Array , order item
	 * �õ���Ʒ��ͼƬ��
	 */
	public function getProductImage($product_one,$item_one){
		$custom_option = $product_one['custom_option'];
		$custom_option_sku = $item_one['custom_option_sku'];
		$image = '';
		# ����ͼƬ
		if(isset($product_one['image']['main']['image'])){
			$image = $product_one['image']['main']['image'];
		}
		$custom_option_image = isset($custom_option[$custom_option_sku]['image']) ? $custom_option[$custom_option_sku]['image'] : '';
		if($custom_option_image){
			$image = $custom_option_image;
		}
		if(!$image){
			$image = $item_one['image']	;
		}
		return $image;
	}
	/**
	 * @property $item_one | Array , order item
	 * ͨ��$item_one ��$item_one['custom_option_sku']��$item_one['custom_option'] , $item_one['spu_options']
	 * @return Array
	 * ��spu��ѡ�����Ժ��Զ�������custom_option �������������һ��ͳһ������
	 */
	public function getProductOptions($item_one){
		$custom_option_sku = $item_one['custom_option_sku'];	
		$custom_option_info_arr = [];
		$custom_option = isset($item_one['custom_option']) ? $item_one['custom_option'] : '';
		if(isset($custom_option[$custom_option_sku]) && !empty($custom_option[$custom_option_sku])){
			$custom_option_info = $custom_option[$custom_option_sku];
			foreach($custom_option_info as $attr=>$val){
				if(!in_array($attr,['qty','sku','price','image'])){ 
					$attr = str_replace('_',' ',$attr);
					$attr = ucfirst($attr);
					$custom_option_info_arr[$attr] = $val;
				}
			}
		}
		$spu_options = isset($item_one['spu_options']) ? $item_one['spu_options'] : '';
		if(is_array($spu_options) && !empty($spu_options)){
			foreach($spu_options as $label => $val){
				$custom_option_info_arr[$label] = $val;
			}
		}
		return $custom_option_info_arr;
	}
	
	/**
	 * @property $productOb | Object�����ͣ�\fecshop\models\mongodb\Product
	 * �õ���Ʒ��spu��Ӧ�������Լ�ֵ��
	 * ���� - spu options���������Ʒ��ͬһ��spu�����ǲ�ͬ��sku��ʱ�����ǵĲ�Ʒ�������
	 * spu attr ��ֵ�ǲ�ͬ�ģ�Ʃ���ӦЬ�ӣ�size �� color ����spu attr������ͬһ��Ь�ӣ�����
	 * ��ͬһ��spu�����ڳ��룬��ɫ��ͬ��Ь�ӣ��ǲ�ͬ��sku�����ǵ�spu attr ���� color �� size��
	 */
	protected function getProductSpuOptions($productOb){
		$custom_option_info_arr = [];
		if(isset($productOb['attr_group']) && !empty($productOb['attr_group'])){
			$productAttrGroup = $productOb['attr_group'];
			$attrInfo = Yii::$service->product->getGroupAttrInfo($productAttrGroup);
			if(is_array($attrInfo) && !empty($attrInfo)){
				$attrs = array_keys($attrInfo);
				\fecshop\models\mongodb\Product::addCustomProductAttrs($attrs);
			}
			$productOb = Yii::$service->product->getByPrimaryKey($productOb['_id']->{'$id'});
			$spuArr = Yii::$service->product->getSpuAttr($productAttrGroup);
			if(is_array($spuArr) && !empty($spuArr)){
				foreach($spuArr as $spu_attr){
					if(isset($productOb[$spu_attr]) && !empty($productOb[$spu_attr])){
						$custom_option_info_arr[$spu_attr] = $productOb[$spu_attr];
					}
				}
			}
		}
		return $custom_option_info_arr ;
	}
	
	/**
	 * @property $items | Array , example:
	 *	$itmes = [
	 *		[
	 *			'item_id' => $one['item_id'],
	 *			'product_id' 		=> $product_id ,
	 *			'sku'				=> $product_one['sku'],
	 *			'name'				=> Yii::$service->store->getStoreAttrVal($product_one['name'],'name'),
	 *			'qty' 				=> $qty ,
	 *			'custom_option_sku' => $custom_option_sku ,
	 *			'product_price' 	=> $product_price ,
	 *			'product_row_price' => $product_row_price ,
	 *			
	 *			'base_product_price' 	=> $base_product_price ,
	 *			'base_product_row_price' => $base_product_row_price ,
	 *			
	 *			'product_name'		=> $product_one['name'],
	 *			'product_weight'	=> $p_wt,
	 *			'product_row_weight'=> $p_wt * $qty,
	 *			'product_url'		=> $product_one['url_key'],
	 *			'product_image'		=> $product_one['image'],
	 *			'custom_option'		=> $product_one['custom_option'],
	 *			'spu_options' 			=> $productSpuOptions,
	 *		]
	 *	];
	 * @property $order_id | Int 
	 * ���涩����item��Ϣ
	*/
	protected function actionSaveOrderItems($items,$order_id,$store){
		if(is_array($items) && !empty($items) && $order_id && $store){
			foreach($items as $item){
				$myOrderItem = new MyOrderItem;
				$myOrderItem['order_id'] = $order_id;
				$myOrderItem['store'] = $store;
				$myOrderItem['created_at'] = time();
				$myOrderItem['updated_at'] = time();
				$myOrderItem['product_id'] = $item['product_id'];
				$myOrderItem['sku'] = $item['sku'];
				$myOrderItem['name'] = $item['name'];
				$myOrderItem['custom_option_sku'] = $item['custom_option_sku'];
				$myOrderItem['image'] = isset($item['product_image']['main']['image']) ? $item['product_image']['main']['image'] : '' ;
				$myOrderItem['weight'] = $item['product_weight'];
				$myOrderItem['qty'] = $item['qty'];
				$myOrderItem['row_weight'] = $item['product_row_weight'];
				$myOrderItem['price'] = $item['product_price'];
				$myOrderItem['base_price'] = $item['product_row_price'];
				$myOrderItem['row_total'] = $item['product_row_price'];
				$myOrderItem['base_row_total'] = $item['base_product_row_price'];
				$myOrderItem['redirect_url'] = $item['product_url'];
				$myOrderItem->save();
			}
		}
	}
	
	
}