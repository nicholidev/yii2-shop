<?php
namespace fecshop\app\appfront\modules\Cms\controllers;
use fecshop\app\appfront\modules\AppfrontController;
class HomeController extends AppfrontController
{
    protected $_currentLayoutFile = 'home.php';
	# ��վ��Ϣ����
    public function actionIndex()
    {
		//echo 111;exit;
		return $this->render($this->action->id,[]);
	}
}
















