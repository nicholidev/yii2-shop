<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\console\modules\Sitemap\controllers;
use Yii;
use yii\base\InlineAction;
use yii\console\Controller;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class XmlController extends Controller
{
	public function actionBegin(){
		Yii::$service->sitemap->beginSiteMap();
	}
	# ��ҳ
	public function actionHome(){
		Yii::$service->sitemap->home();
	}
	# ����ҳ���ҳ������
	public function actionCategorypagecount(){
		echo Yii::$service->sitemap->categorypagecount();
	}
	# ���ɷ���ҳ��
	public function actionCategory($pageNum){
		Yii::$service->sitemap->category($pageNum);
	}
	# ��Ʒҳ���ҳ������
	public function actionProductpagecount(){
		echo Yii::$service->sitemap->productpagecount();
	}
	# ���ɲ�Ʒҳ��
	public function actionProduct($pageNum){
		Yii::$service->sitemap->product($pageNum);
	}
	# cms pageҳ���ҳ������
	public function actionCmspagepagecount(){
		echo Yii::$service->sitemap->cmspagepagecount();
	}
	# ����cms pageҳ���sitemap
	public function actionCmspage($pageNum){
		Yii::$service->sitemap->cmspage($pageNum);
	}
	
	
	public function actionEnd(){
		Yii::$service->sitemap->endSiteMap();
	}
	
		
}