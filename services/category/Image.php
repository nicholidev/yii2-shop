<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\category;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Image extends Service
{
	/**
	 * absolute image save floder
	 */
	public $imageFloder = 'media/catalog/category';
	/**
	 * upload image max size
	 */
	public $maxUploadMSize;
	/**
	 * allow image type
	 */
	public $allowImgType = [
		'image/jpeg',
		'image/gif',
		'image/png',
		'image/jpg',
		'image/pjpeg',
	];
	
	/**
	 * �õ��������ͼƬ������Ը�Ŀ¼��url·��
	 */
	protected function actionGetBaseUrl(){
		return Yii::$service->image->GetImgUrl($this->imageFloder,'common');
	}
	/**
	 * �õ��������ͼƬ������Ը�Ŀ¼���ļ���·��
	 */
	protected function actionGetBaseDir(){
		return Yii::$service->image->GetImgDir($this->imageFloder,'common');
	}
	/**
	 * ͨ������ͼƬ�����·���õ���ƷͼƬ��url
	 */
	protected function actionGetUrl($str){
		return Yii::$service->image->GetImgUrl($this->imageFloder.$str,'common');
	}
	/**
	 * ͨ����ƷͼƬ�����·���õ���ƷͼƬ�ľ���·��
	 */
	protected function actionGetDir(){
		return Yii::$service->image->GetImgDir($this->imageFloder.$str,'common');
	}
	
	
	/**
	 * @property $param_img_file | Array .
	 * upload image from web page , you can get image from $_FILE['XXX'] , 
	 * $param_img_file is get from $_FILE['XXX'].
	 * return , if success ,return image saved relative file path , like '/b/i/big.jpg'
	 * if fail, reutrn false;
	 */
	protected function actionSaveCategoryUploadImg($FILE){
		Yii::$service->image->imageFloder = $this->imageFloder;
		Yii::$service->image->allowImgType = $this->allowImgType;
		if($this->maxUploadMSize){
			Yii::$service->image->setMaxUploadSize($this->maxUploadMSize);
		}
		return Yii::$service->image->saveUploadImg($FILE);
	}
	
	
}