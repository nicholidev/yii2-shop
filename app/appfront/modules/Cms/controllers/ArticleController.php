<?php
namespace fecshop\app\appfront\modules\Cms\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
class ArticleController extends AppfrontController
{
    public function init(){
		parent::init();
	}
	# ��վ��Ϣ����
    public function actionIndex()
    {
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	public function actionChangecurrency(){
		$currency = \fec\helpers\CRequest::param('currency');
		Yii::$service->page->currency->setCurrentCurrency($currency);
	}
}
















