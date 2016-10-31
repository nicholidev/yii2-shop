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
class Favorite extends ActiveRecord
{
    
	
	public static function collectionName()
    {
	   return 'favorite';
    }
	
	
    public function attributes()
    {
		$origin = [
			'_id', 
			'product_id', 	# ��Ʒid �ַ���
			'user_id',		# �û�id int����
			'created_at',	# ����ʱ�� int
			'updated_at',	# ����ʱ�� int
			'store'			# Store ��ǰstore
		];
		
		return $origin;
    }
	
	
	
	
	
	
	
	
	
}