<?php
namespace fecshop\app\appapi\modules\V1;
use Yii;
use fecshop\app\appapi\modules\AppapiModule;
class Module extends AppapiModule
{
	public $blockNamespace;
    public function init()
    {
		# ���´������ָ��
		$nameSpace = __NAMESPACE__;
		# web controller
		$this->controllerNamespace 	= 	$nameSpace . '\\controllers';
		$this->blockNamespace 		= 	$nameSpace . '\\block';
		# ָ��Ĭ�ϵ�man�ļ�
		//$this->layout = "home.php";
		//Yii::$service->page->theme->layoutFile = 'home.php';
		parent::init();  
		
    }
}
