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
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Cache extends Service
{
	# ����ҳ��cache������
	public $cacheConfig;
	# cache �ܿ���
	public $enable;
	
	/**
	 * @property $cacheKey | String , ����Ļ������֣�Ʃ�� product  category 
	 * @return boolean, ���enableΪtrue���򷵻�Ϊtrue
	 */
	public function isEnable($cacheKey){
		if($this->enable && isset($this->cacheConfig[$cacheKey]['enable'])){
			return $this->cacheConfig[$cacheKey]['enable'];
		}else{
			return false;
		}
	}
	
	
	/**
	 * @property $cacheKey | String , ����Ļ������֣�Ʃ�� product  category 
	 * @return int, ���enableΪtrue���򷵻�Ϊtrue
	 */
	public function timeout($cacheKey){
		if(isset($this->cacheConfig[$cacheKey]['timeout'])){
			return $this->cacheConfig[$cacheKey]['timeout'];
		}else{
			return 0;
		}
	}
	
	
	/**
	 * @property $cacheKey | String , ����Ļ������֣�Ʃ�� product  category 
	 * @return string, ���enableΪtrue���򷵻�Ϊtrue
	 */
	public function disableUrlParam($cacheKey){
		if(isset($this->cacheConfig[$cacheKey]['disableUrlParam'])){
			return $this->cacheConfig[$cacheKey]['disableUrlParam'];
		}else{
			return '';
		}
	}
	
	/**
	 * @property $cacheKey | String , ����Ļ������֣�Ʃ�� product  category 
	 * @return string, ���enableΪtrue���򷵻�Ϊtrue
	 * url�Ĳ�������һЩ������Ϊ����Ψһ�����ݣ�Ʃ��p����ҳ��ֵ��
	 * 
	 */
	public function cacheUrlParam($cacheKey){
		if(isset($this->cacheConfig[$cacheKey]['cacheUrlParam'])){
			return $this->cacheConfig[$cacheKey]['cacheUrlParam'];
		}else{
			return '';
		}
	}
	
	
	
	
	
	
	
}