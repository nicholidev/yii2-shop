<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductController extends AppfrontController
{
    public function init(){
		parent::init();
		Yii::$service->page->theme->layoutFile = 'product_view.php';
	}
	# ��վ��Ϣ����
    public function actionIndex()
    {
		//echo 1;exit;
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	# ajax �õ���Ʒ���빺�ﳵ�ļ۸�
	public function actionGetcoprice(){
		$custom_option_sku = Yii::$app->request->get('custom_option_sku');
		$product_id = Yii::$app->request->get('product_id');
		$qty = Yii::$app->request->get('qty');
		$cart_price = 0;
		$custom_option_price = 0;
		$product = Yii::$service->product->getByPrimaryKey($product_id);
		$cart_price = Yii::$service->product->price->getCartPriceByProductId($product_id,$qty,$custom_option_sku);
		if(!$cart_price){
			return;
		}
		$price_info = [
			'price' => $cart_price,
		];
		
		$priceView = [
			'view'	=> 'catalog/product/index/price.php'
		];
		$priceParam = [
			'price_info' => $price_info,
		];
					
		echo  json_encode([
			'price' =>Yii::$service->page->widget->render($priceView,$priceParam),
		]);
		exit;			
	
	}
	
	
}
















