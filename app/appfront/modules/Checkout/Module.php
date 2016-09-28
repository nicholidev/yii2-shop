<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Checkout;
use Yii;
use fecshop\app\appfront\modules\AppfrontModule;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
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
		//} elseif (Yii::$app instanceof \yii\console\Application) {
		//	$this->controllerNamespace 	= 	$nameSpace . '\\console\\controllers';
		//	$this->blockNamespace 	= 	$nameSpace . '\\console\\block';
		}
		//$this->_currentDir			= 	__DIR__ ;
		//$this->_currentNameSpace	=   __NAMESPACE__;
		
		# ָ��Ĭ�ϵ�man�ļ�
		//$this->layout = "home.php";
		Yii::$service->page->theme->layoutFile = 'main.php';
		parent::init();  
		
    }
}
