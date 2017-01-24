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
	# ����Ĭ��״̬��Ҳ�����û���������ۺ��״̬��ǰ���ǿͻ���������Ϣ��Ҫ��˵�ǰ���£�����ͻ���Ϣ����Ҫ��˵Ļ��������ACTIVE_STATUS��
	const NOACTIVE_STATUS 	= 10;
	# ���ͨ����״̬
	const ACTIVE_STATUS 	= 1;
	# ��˾ܾ���״̬
	const REFUSE_STATUS 	= 2;
	
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
			'product_sku',
			'product_id',
			'rate_star',
		    'name',
			'user_id',
			'ip',
	        'summary', 
	        'review_content', 		# ���۵�����
			'review_date',			# ���۵�������ʱ��
			'store',			# store
			'lang_code',		# ����
			'status',			# ���״̬ 10����δ��ˣ�1��������ˡ�
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