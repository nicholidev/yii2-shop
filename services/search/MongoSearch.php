<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\search;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mongodb\Search;
use fecshop\services\Service;
use fecshop\models\mongodb\Product;
/**
 * Search
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class MongoSearch extends Service implements SearchInterface
{
	public $searchIndexConfig;
	public $searchLang;
	public function init(){
		/**
		 * 初始化search model 的属性，将需要过滤的属性添加到search model的类属性中。
		 *  $searchModel 		= new Search;
		 *  $searchModel->attributes();	
		 *	上面的获取的属性，就会有下面添加的属性了。
		 *  将产品同步到搜索表的时候，就会把这些字段也添加进去
		 */
		$filterAttr = Yii::$service->search->filterAttr;
		if(is_array($filterAttr) && !empty($filterAttr)){
			Search::$_filterColumns =  $filterAttr;
		}
	}
	
	/**
	 * 创建索引
	 */ 
	protected function actionInitFullSearchIndex(){
		
		$config1 = [];
		$config2 = [];
		//var_dump($this->searchIndexConfig);exit;
		if(is_array($this->searchIndexConfig) && (!empty($this->searchIndexConfig))){
			foreach($this->searchIndexConfig as $column => $weight){
				$config1[$column] = 'text';
				$config2['weights'][$column] = (int)$weight; 
			}
		}
		
		//$langCodes = Yii::$service->fecshoplang->allLangCode;
		if(!empty($this->searchLang) && is_array($this->searchLang)){
			foreach($this->searchLang as $langCode => $mongoSearchLangName){
				/**
				 * 如果语言不存在，譬如中文，mongodb的fullSearch是不支持中文的，
				 * 这种情况是不能搜索的。
				 * 能够进行搜索的语言列表：https://docs.mongodb.com/manual/reference/text-search-languages/#text-search-languages
				 */
				if($mongoSearchLangName){
					Search::$_lang 	= $langCode;
					$searchModel 	= new Search;
					$colltionM 		= $searchModel::getCollection();
					$config2['default_language'] = $mongoSearchLangName;
					$colltionM->mongoCollection->ensureIndex($config1,$config2);
				}
			}
		}
		/*
		$searchModel::getCollection()->ensureIndex(  
			[  
				'name' => 'text',  
				'description' => 'text',  
			],  
			[  
				  
				'weights' => [  
					'name' => 10,  
					'description' => 5,  
				],  
				'default_language'=>$store,  
			]  
		);
		*/
	}
	/**
	 * @property $product_ids |　Array ，里面的子项是MongoId类型。
	 * 将产品表的数据同步到各个语言对应的搜索表中。
	 */
	protected function actionSyncProductInfo($product_ids,$numPerPage){
		if(is_array($product_ids) && !empty($product_ids)){
			$productPrimaryKey  = Yii::$service->product->getPrimaryKey();
			$searchModel 		= new Search;
			$filter['select'] 	= $searchModel->attributes();
			$filter['asArray']	= true;
			$filter['where'][]	= ['in',$productPrimaryKey,$product_ids];
			$filter['numPerPage']= $numPerPage;
			$filter['pageNum']	= 1;
			$coll = Yii::$service->product->coll($filter);
			if(is_array($coll['coll']) && !empty($coll['coll'])){
				foreach($coll['coll'] as $one){
					//$langCodes = Yii::$service->fecshoplang->allLangCode;
					//if(!empty($langCodes) && is_array($langCodes)){
					//	foreach($langCodes as $langCodeInfo){
					$one_name 				= $one['name'];
					$one_description 		= $one['description'];
					$one_short_description 	= $one['short_description'];
					if(!empty($this->searchLang) && is_array($this->searchLang)){
						foreach($this->searchLang as $langCode => $mongoSearchLangName){
							Search::$_lang	= $langCode;
							$searchModel = Search::findOne(['_id' => $one['_id']]);
							if(!$searchModel['_id']){
								$searchModel 	= new Search;
							}
							$one['name'] 		= Yii::$service->fecshoplang->getLangAttrVal($one_name,'name',$langCode);
							$one['description'] = Yii::$service->fecshoplang->getLangAttrVal($one_description,'description',$langCode);
							$one['short_description'] = Yii::$service->fecshoplang->getLangAttrVal($one_short_description,'short_description',$langCode);
							$one['sync_updated_at'] = time();
							Yii::$service->helper->ar->save($searchModel,$one);
							if($errors = Yii::$service->helper->errors->get()){
								# 报错。
								echo  $errors; 
								//return false;
							}
						}
					}
				}
			}
		}
		return true;
	}
	
	
	/**
	 * 批量更新过程中，被更新的产品都会更新字段sync_updated_at
	 * 删除xunSearch引擎中sync_updated_at小于$nowTimeStamp的字段
	 */
	protected function actionDeleteNotActiveProduct($nowTimeStamp){
		echo "begin delete Mongodb Search Date \n";
		//$langCodes = Yii::$service->fecshoplang->allLangCode;
		//if(!empty($langCodes) && is_array($langCodes)){
		//	foreach($langCodes as $langCodeInfo){
		if(!empty($this->searchLang) && is_array($this->searchLang)){
			foreach($this->searchLang as $langCode => $mongoSearchLangName){
				Search::$_lang	= $langCode;
				Search::deleteAll([
					'<','sync_updated_at',(int)$nowTimeStamp
				]);
			}
		}
		
	}
	
	protected function actionRemoveByProductId($product_id){
		//echo 1;exit;
		if(!empty($this->searchLang) && is_array($this->searchLang)){
			foreach($this->searchLang as $langCode => $mongoSearchLangName){
				Search::$_lang	= $langCode;
				Search::deleteAll([
					'_id' => $product_id,
				]);
			}
		}
		return true;
	}
	
	/**
	 * 得到搜索的产品列表
	 */
	protected function actionGetSearchProductColl($select,$where,$pageNum,$numPerPage,$product_search_max_count){
		$filter = [
			'pageNum'	  	=> $pageNum,
			'numPerPage'  	=> $numPerPage,
			'where'  		=> $where,
			'product_search_max_count' => $product_search_max_count,		
			'select' 	 	 => $select,
		];
		//var_dump($filter);exit;
		$collection = $this->fullTearchText($filter);
		$collection['coll'] = Yii::$service->category->product->convertToCategoryInfo($collection['coll']);
		return $collection;
	}
	
	/**
	 * 全文搜索
	 * $filter Example:
	 *	$filter = [
	 *		'pageNum'	  => $this->getPageNum(),
	 *		'numPerPage'  => $this->getNumPerPage(),
	 *		'where'  => $this->_where,
	 *		'product_search_max_count' => 	Yii::$app->controller->module->params['product_search_max_count'],		
	 *		'select' 	  => $select,
	 *	];
	 *  因为mongodb的搜索涉及到计算量，因此产品过多的情况下，要设置 product_search_max_count的值。减轻服务器负担
     *  因为对客户来说，前10页的产品已经足矣，后面的不需要看了，限定一下产品个数，减轻服务器的压力。	 
	 *  多个spu，取score最高的那个一个显示。
	 *  按照搜索的匹配度来进行排序，没有其他排序方式
	 */
	protected function fullTearchText($filter){
		$where	 				= $filter['where'];
		$product_search_max_count	= $filter['product_search_max_count'] ? $filter['product_search_max_count'] : 1000;
		
		$select 	= $filter['select'];
		$pageNum 	= $filter['pageNum'];
		$numPerPage = $filter['numPerPage'];
		$orderBy 	= $filter['orderBy'];
		# 
		/**
		 * 说明：1.'search_score'=>['$meta'=>"textScore" ，这个是text搜索为了排序，
		 *		    详细参看：https://docs.mongodb.com/manual/core/text-search-operators/
		 *		 2. sort排序：search_score是全文搜索匹配后的得分，score是product表的一个字段，这个字段可以通过销售量或者其他作为参考设置。
		 */
		Search::$_lang	= Yii::$service->store->currentLangCode;
		//$search_data = Search::getCollection();				
		
		//$mongodb = Yii::$app->mongodb;
		//$search_data = $mongodb->getCollection('full_search_product_en')
			
		$search_data = Search::getCollection()->find($where,['search_score'=>['$meta'=>"textScore" ],'id' => 1 ,'spu'=> 1,'score' => 1,])
			->sort( ['search_score'=> [ '$meta'=> 'textScore' ],'score' => -1] )
			->limit($product_search_max_count)
			;
		/**
		 * 通过下面的数组，在spu相同的多个sku产品，只显示一个，因为上面已经排序，
		 * 因此，spu相同的sku产品，显示的是score最高的一个。
		 */
		$data = [];
		foreach($search_data as $one){
			if(!isset($data[$one['spu']])){
				$data[$one['spu']] = $one;
			}
		}
		$count = count($data);
		$offset = ($pageNum -1)*$numPerPage;
		$limit  =  $numPerPage;
		$productIds = [];
		foreach($data as $d){
			$productIds[] = $d['_id'];
		}
		$productIds = array_slice($productIds, $offset, $limit);
		if(!empty($productIds)){
			$query = Product::find()->asArray()
					->select($select)
					->where(['_id'=> ['$in'=>$productIds]])
					;
			$data  = $query->all();
			/**
			 * 下面的代码的作用：将结果按照上面in查询的顺序进行数组的排序，使结果和上面的搜索结果排序一致（_id）。
			 */
			$s_data = [];
			foreach($data as $one){
				$_id = $one['_id']->{'$id'};
				$s_data[$_id] = $one;
			}
			$return_data = [];
			foreach($productIds as $product_id){
				$return_data[] = $s_data[$product_id->{'$id'}];
			}
			return [
				'coll' => $return_data ,
				'count'=> $count,
			];
		}
		
	}
	
	
	/**
	 * @property $filter_attr | String 需要进行统计的字段名称
	 * @propertuy $where | Array  搜索条件。这个需要些mongodb的搜索条件。
	 * 得到的是个属性，以及对应的个数。
	 * 这个功能是用于前端分类侧栏进行属性过滤。
	 */
	protected function actionGetFrontSearchFilter($filter_attr,$where){
		if(empty($where))
			return [];
		$group['_id'] 	= '$'.$filter_attr;
		$group['count'] = ['$sum'=> 1];
		$project 		= [$filter_attr => 1];
		$pipelines = [
			[
				'$match' 	=> $where,
			],
			[
				'$project' 	=> $project
			],
			[
				'$group'	=> $group,
			],
		];
		Search::$_lang	= Yii::$service->store->currentLangCode;
		$filter_data = Search::getCollection()->aggregate($pipelines);
		return $filter_data;
	}
}











