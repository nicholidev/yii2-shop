<?php
namespace fecshop\app\appfront\modules\Catalog\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
class CategoryController extends AppfrontController
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
	
	
}
















