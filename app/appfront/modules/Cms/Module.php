<?php
namespace fecshop\app\appfront\modules\Cms;
use Yii;
use fecshop\app\appfront\modules\AppfrontModule;
class Module extends AppfrontModule
{
   
    public function init()
    {
		# ���´������ָ��
		
		# web controller
		if (Yii::$app instanceof \yii\web\Application) {
			$this->controllerNamespace 	= 	__NAMESPACE__ . '\\controllers';
		# console controller
		} elseif (Yii::$app instanceof \yii\console\Application) {
			$this->controllerNamespace 	= 	__NAMESPACE__ . '\\console';
		}
		//$this->_currentDir			= 	__DIR__ ;
		//$this->_currentNameSpace	=   __NAMESPACE__;
		
		# ָ��Ĭ�ϵ�man�ļ�
		//$this->layout = "/main_ajax.php";
		parent::init();  
		
    }
}
