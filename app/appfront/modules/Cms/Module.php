<?php
namespace fecshop\app\appfront\modules\Cms;
use Yii;
use fecshop\app\appfront\modules\AppfrontModule;
class Module extends AppfrontModule
{
   public $blockNamespace;
    public function init()
    {
		# ���´������ָ��
		$nameSpace = __NAMESPACE__;
		# web controller
		if (Yii::$app instanceof \yii\web\Application) {
			$this->controllerNamespace 	= 	$nameSpace . '\\controllers';
			$this->blockNamespace 	= 	$nameSpace . '\\block';
		# console controller
		} elseif (Yii::$app instanceof \yii\console\Application) {
			$this->controllerNamespace 	= 	$nameSpace . '\\console\\controllers';
			$this->blockNamespace 	= 	$nameSpace . '\\console\\block';
		}
		//$this->_currentDir			= 	__DIR__ ;
		//$this->_currentNameSpace	=   __NAMESPACE__;
		
		# ָ��Ĭ�ϵ�man�ļ�
		//$this->layout = "home.php";
		Yii::$app->page->theme->layoutFile = 'home.php';
		parent::init();  
		
    }
}
