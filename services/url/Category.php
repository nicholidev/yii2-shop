<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\url;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\Service;
use fecshop\services\url\rewrite\RewriteMysqldb;
use fecshop\services\url\rewrite\RewriteMongodb;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Category extends Service
{
	/**
	 * ������ֵת����url��ʽ���ַ�������������url
	 */
	protected function actionAttrValConvertUrlStr($strVal){
		if(!preg_match("/^[A-Za-z0-9-_ &]+$/",$strVal)){
			throw new InvalidValueException('"'.$strVal .'":contain special str , you can only contain special string [A-Za-z0-9-_ &]');
		}
		$convert = $this->strUrlRelation();
		foreach($convert as $originStr => $nStr){
			$strVal = str_replace($originStr,$nStr,$strVal);
		}
		return $strVal;
	}
	/**
	 * ��url��ʽ���ַ���ת��������ֵ�����ڽ���url���õ���Ӧ������ֵ
	 */
	protected function actionUrlStrConvertAttrVal($urlStr){
		$convert = $this->strUrlRelation();
		foreach($convert as $originStr => $nStr){
			$urlStr = str_replace($nStr,$originStr,$urlStr);
		}
		return $urlStr;
	}
	
	protected function strUrlRelation(){
		return [
			' ' => '!',
			'&' => '@',
		];
	}
	/**
	 * 
	 */
	/**
	 * �ڷ����������������ԣ��õ�ѡ��������Ե�url
	 * @property $attrUrlStr|String ���Ե�url�������ַ���
	 * @property $val|String ���Զ�Ӧ��ֵ��δurl�����ֵ
	 * @property $p|String  ��url��������ʾ��ҳ�Ĳ�����һ����p����ʾ��
	 * @property $pageBackToOne|boolean �Ƿ���p��ҳ���ع��һҳ
	 */
	protected function actionGetFilterChooseAttrUrl($attrUrlStr,$val,$p='',$pageBackToOne=true){
		
		$val = $this->attrValConvertUrlStr($val);
		$str = $attrUrlStr.'='.$val;
		$currentRequestVal = Yii::$app->request->get($attrUrlStr);
		$originPUrl = '';
		if($pageBackToOne && $p){
			$pVal = Yii::$app->request->get($p);
			if($pVal){
				$originPUrl  = $p.'='.$pVal;
				$afterPUrl   = $p.'=1';
			}
		}
		
		if($currentRequestVal){
			$originAttrUrlStr = $attrUrlStr.'='.$currentRequestVal;
			$currentUrl = Yii::$service->url->getCurrentUrl();
			if($originAttrUrlStr == $str){
				//return str_replace($originAttrUrlStr,$str,$currentUrl);
				$url = $currentUrl;
				if(strstr($currentUrl,'?'.$originAttrUrlStr.'&')){
					$url = str_replace('?'.$originAttrUrlStr.'&','?',$currentUrl);
				}else if(strstr($currentUrl,'?'.$originAttrUrlStr)){
					$url = str_replace('?'.$originAttrUrlStr,'',$currentUrl);
				}else if(strstr($currentUrl,'&'.$originAttrUrlStr)){
					$url = str_replace('&'.$originAttrUrlStr,'',$currentUrl);
				}
				if($originPUrl){
					$url = str_replace($originPUrl,$afterPUrl,$url);
				}
				return [
					'url'   	=> $url,
					'selected'	=> true,
				];
			}else{
				if($originPUrl){
					$currentUrl = str_replace($originPUrl,$afterPUrl,$currentUrl);
				}
				return [
					'url'   	=> str_replace($originAttrUrlStr,$str,$currentUrl),
					'selected'	=> false,
				];
			}
			return str_replace($originAttrUrlStr,$str,$currentUrl);
		}else{
			$currentUrl = Yii::$service->url->getCurrentUrl();
			if(strstr($currentUrl,'?')){
				if($originPUrl){
					$currentUrl = str_replace($originPUrl,$afterPUrl,$currentUrl);
				}
				return [
					'url'   	=> $currentUrl.'&'.$str,
					'selected'	=> false
				];
			}else{
				if($originPUrl){
					$currentUrl = str_replace($originPUrl,$afterPUrl,$currentUrl);
				}
				return [
					'url'   	=> $currentUrl.'?'.$str,
					'selected'	=> false
				];
			}
		}
		
	}
	
	/**
	 * �õ������url
	 * @property $arr|Array sort���ֶκ�ֵ  dir���ֶκ�ֵ
	 * @property $p|String  ��url��������ʾ��ҳ�Ĳ�����һ����p����ʾ��
	 * @property $pageBackToOne|boolean �Ƿ���p��ҳ���ع��һҳ
	 */
	protected function actionGetFilterSortAttrUrl($arr,$p='',$pageBackToOne=true){
		$sort 		= $arr['sort']['key'];
		$sortVal 	= $arr['sort']['val'];
		$dir 		= $arr['dir']['key'];
		$dirVal 	= $arr['dir']['val'];
		
		$originPUrl = '';
		if($pageBackToOne && $p){
			$pVal = Yii::$app->request->get($p);
			if($pVal){
				$originPUrl  = $p.'='.$pVal;
				$afterPUrl   = $p.'=1';
			}
		}
		
		$sortVal = $this->attrValConvertUrlStr($sortVal);
		$sortStr = $sort.'='.$sortVal;
		$currentSortVal = Yii::$app->request->get($sort);
		
		$dirVal = $this->attrValConvertUrlStr($dirVal);
		$dirStr = $dir.'='.$dirVal;
		$currentDirVal = Yii::$app->request->get($dir);
		
		$str = $sortStr.'&'.$dirStr;
		if($currentSortVal && $currentDirVal){
			$originAttrUrlStr = $sort.'='.$currentSortVal.'&'.$dir.'='.$currentDirVal;
			$currentUrl = Yii::$service->url->getCurrentUrl();
			
			if($originAttrUrlStr == $str){
				//return str_replace($originAttrUrlStr,$str,$currentUrl);
				$url = $currentUrl;
				if(strstr($currentUrl,'?'.$originAttrUrlStr.'&')){
					$url = str_replace('?'.$originAttrUrlStr.'&','?',$currentUrl);
				}else if (strstr($currentUrl,'?'.$originAttrUrlStr)){
					$url = str_replace('?'.$originAttrUrlStr,'',$currentUrl);
				}else if(strstr($currentUrl,'&'.$originAttrUrlStr)){
					$url = str_replace('&'.$originAttrUrlStr,'',$currentUrl);
				}
				if($originPUrl){
					$url = str_replace($originPUrl,$afterPUrl,$url);
				}
				return [
					'url'   	=> $url,
					'selected'	=> true,
				];
			}else{
				if($originPUrl){
					$currentUrl = str_replace($originPUrl,$afterPUrl,$currentUrl);
				}
				return [
					'url'   	=> str_replace($originAttrUrlStr,$str,$currentUrl),
					'selected'	=> false,
				];
			}
			return str_replace($originAttrUrlStr,$str,$currentUrl);
		}else{
			$currentUrl = Yii::$service->url->getCurrentUrl();
			if(strstr($currentUrl,'?')){
				if($originPUrl){
					$currentUrl = str_replace($originPUrl,$afterPUrl,$currentUrl);
				}
				return [
					'url'   	=> $currentUrl.'&'.$str,
					'selected'	=> false
				];
			}else{
				if($originPUrl){
					$currentUrl = str_replace($originPUrl,$afterPUrl,$currentUrl);
				}
				return [
					'url'   	=> $currentUrl.'?'.$str,
					'selected'	=> false
				];
			}
		}
		
	}
	
	/**
	 * �õ���ѡ��������Ե�url
	 */
	/*
	protected function actionGetFilterUnChooseAttrUrl($attrUrlStr,$val){
		$val = $this->attrValConvertUrlStr($val);
		$str = $attrUrlStr.'='.$val;
		$currentUrl = Yii::$service->url->getCurrentUrl();
		$currentUrl = str_replace($str,'',$currentUrl);
		return $currentUrl ;
	}
	*/
	
	
	
	
	
	
}