<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
/**
 * Order services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Order extends Service
{
	
	protected $orderStatus = [
		'pending','processing'
	];
	
	/**
	 * get all order collection
	 */
	public function getOrderList(){
		
		
	}
	
	/**
	 * get order list by customer account id.
	 */
	public function getAccountOrderList(){
		
		
	}
	/**
	 * get order list by customer account id.
	 */
	public function changeOrderStatus(){
		
		
	}
	
	/**
	 * get order by Id.
	 */
	public function getOrderById(){
		
	}
	
	
	
	
	
}