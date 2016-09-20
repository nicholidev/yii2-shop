<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mongodb;
use Yii;
use yii\mongodb\ActiveRecord;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Search extends ActiveRecord
{
    /**
	 * ���ԣ���ʹ��model֮ǰ�����������ԣ����򱨴�
	 */
	public static $_lang;
	public static $_filterColumns;
   
	public static function collectionName()
    {
		if(self::$_lang){
			return 'full_search_product_'.self::$_lang;
		}else{
			//throw new InvalidValueException('search class $_lang is empty, you must set search model class variable $_lang before  use it');
			return 'full_search_product_no_lang';
		}
    }
	
	
   
    public function attributes()
    {
		$origin =  [
			'_id', 
		    'name',
			'spu',
	        'sku',
			'score',
	        'status',
			'is_in_stock',
			'url_key', 
			'price', 
			'cost_price', 
			'special_price', 
			'special_from', 
			'special_to', 
			'final_price',   # ����������ռ۸����ͨ���ű���ֵ��
			'image',
			'short_description',
			'description',
			'created_at',
			'sync_updated_at',  # ͬ����Ʒ����Ϣ���������ʱ�����
		];
		if(is_array(self::$_filterColumns) && !empty(self::$_filterColumns)){
			$origin = array_merge($origin,self::$_filterColumns);
			$origin = array_unique($origin);
		}
		return $origin;
    }
	
	
	
	
	
	
	
	
	
}