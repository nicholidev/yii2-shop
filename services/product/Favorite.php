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
use fec\helpers\CDir;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;

use fecshop\models\mongodb\product\Favorite as FavoriteModel;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Favorite extends Service
{
	
	protected function actionGetPrimaryKey(){
		return '_id';
	}
	
	protected function actionGetByPrimaryKey($val){
		$one = FavoriteModel::findOne($val);
		if($one[$this->getPrimaryKey()]){
			return $one;
		}else{
			return new FavoriteModel;
		}
	}
	
	protected function actionGetByProductIdAndUserId($product_id,$user_id=''){
		
		if(!$user_id){
			$identity = Yii::$app->user->identity;
			$user_id  = $identity['id'];
		}
		if($user_id){
			$one = FavoriteModel::findOne([
				'product_id' => $product_id,
				'user_id'	 => $user_id,
			]);
			if($one[$this->getPrimaryKey()]){
				return $one;
			}
		}
	}
	
	protected function actionAdd($product_id,$user_id){
		$user_id = (int)$user_id;
		$productPrimaryKey =  Yii::$service->product->getPrimaryKey();
		$product = Yii::$service->product->getByPrimaryKey($product_id);
		# ����Ʒ�Ƿ���ڣ���������ڣ����������Ϣ��
		if(!isset($product[$productPrimaryKey])){
			Yii::$service->helper->errors->add('product is not exist!');
			return ;
		}
		//echo $product_id;exit;
		$favoritePrimaryKey = Yii::$service->product->favorite->getPrimaryKey();
		$one = FavoriteModel::findOne([
			'product_id' => $product_id,
			'user_id'	 => $user_id,
		]);
		if(isset($one[$favoritePrimaryKey])){
			$one->updated_at = time();
			$one->store = Yii::$service->store->currentStore;
			$one->save();
			return true;
		}
		$one = new FavoriteModel;
		$one->product_id = $product_id;
		$one->user_id = $user_id;
		$one->created_at = time();
		$one->updated_at = time();
		$one->store = Yii::$service->store->currentStore;
		$one->save();
		return true;
	}
	/*
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> [$this->getPrimaryKey() => SORT_DESC, 'sku' => SORT_ASC ],
	 * 		'where'			=> [
				['>','price',1],
				['<=','price',10]
	 * 			['sku' => 'uk10001'],
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 */
	protected function actionList($filter){
		$query = FavoriteModel::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		return [
			'coll' => $query->all(),
			'count'=> $query->count(),
		];
		
	}
	/**
	 * @property $favorite_id|String 
	 * ͨ��idɾ��favorite
	 */
	protected function actionCurrentUserRemove($favorite_id){
		$identity = Yii::$app->user->identity;
		$user_id  = $identity['id'];
		
		$one = FavoriteModel::findOne([
			'_id' 		=> new \MongoId($favorite_id),
			'user_id'	=> $user_id,
		]);
		if($one['_id']){
			$one->delete();
			return true;
		}
		return;
	}
	
	
	
}