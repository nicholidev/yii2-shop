<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\helpers;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review{
	
	
	# ��ʼ����ǰappfront�����ã�����service�ĳ�ʼ���á�
	public static function initReviewConfig(){
		# �õ�ǰ�����ã�����service�Ĺ������á�
		$reviewParam = Yii::$app->getModule("catalog")->params['review'];
		if(isset($reviewParam['filterByStore'])){
			Yii::$service->product->review->filterByStore = $reviewParam['filterByStore'];
		}
		if(isset($reviewParam['filterByLang'])){
			Yii::$service->product->review->filterByLang = $reviewParam['filterByLang'];
		}
		# ����ӵ������Ƿ���Ҫ���
		if(isset($reviewParam['newReviewAudit'])){
			Yii::$service->product->review->newReviewAudit = $reviewParam['newReviewAudit'];
		}
		
	}
	
	
	
	
	
}