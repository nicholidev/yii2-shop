<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\product;
use Yii;
use yii\base\InvalidConfigException;
use fecshop\services\ChildService;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class BestSell extends ChildService
{
	
	/**
	 *	得到全部产品中热销的产品
	 */
	 
	public function getCategoryProduct()
	{
		return 'category best sell product';
	}
	
	/**
	 *	得到全部产品中热销的产品
	 */
	public function getProduct(){
		
		
	}
	
	
	
 
}