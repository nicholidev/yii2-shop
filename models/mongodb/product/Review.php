<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mongodb\product;
use Yii;
use yii\mongodb\ActiveRecord;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review extends ActiveRecord
{
    public static $_customAttrs;
	
	
	
	public static function collectionName()
    {
	   return 'review';
    }
	# ��̬�����ֶΡ�
	public static function addCustomAttrs($attrs){
		self::$_customAttrs = $attrs;
	}
	
    public function attributes($origin=false)
    {
		$origin = [
			'_id', 
			'product_spu',
			'product_id',
			'rate_star',
		    'name',
	        'summary', 
	        'review_content', 
			'review_date',			# 
			'store',			# store
			'lang_code',		# ����
			'status',			# ���״̬
			'audit_user',		# ����˺�
			'audit_date',		# ���ʱ��
	    ];
		if($origin){ # ȡԭʼ������
			return $origin;
		}
	    if(is_array(self::$_customAttrs) && !empty(self::$_customAttrs)){
			$origin = array_merge($origin,self::$_customAttrs);
		}
		return $origin;
    }
	
	
	
	
	
	
	
	
	
}