<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
# ���ļ���app/web/index.php �����롣
# fecshop - appfront �ĺ���ģ��
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename){
	$modules = array_merge($modules,require($filename));
}
# �˴�Ҳ������дfecshop������������á�
return [
	'modules'=>$modules,
	/* only config in front web */
	'bootstrap' => ['store'],
	'params'	=> [
		/* appfront base theme dir   */
		'appfrontBaseTheme' 	=> '@fecshop/app/apphtml5/theme/base/front',
		'appfrontBaseLayoutName'=> 'main.php',
		'appName' => 'apphtml5',
	],
	# language config.
	'components' => [
		'i18n' => [
			'translations' => [
				'apphtml5' => [
					//'class' => 'yii\i18n\PhpMessageSource',
					'class' => 'fecshop\yii\i18n\PhpMessageSource',
					'basePaths' => [
						'@fecshop/app/apphtml5/languages',
						'@apphtml5/languages',
					],
					'sourceLanguage' => 'en_US', # ��� en_US Ҳ�뷭�룬��ô���Ըĳ�en_XX��
				],
			],
		],
		
		'user' => [
			'identityClass' => 'fecadmin\models\AdminUser',
			'enableAutoLogin' => true,
		],
		
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		
		'urlManager' => [
			'rules' => [
				'' => 'cms/home/index',
			],
		],
		
		
		'request' => [
			'class' => 'fecshop\yii\web\Request',
			/*
			'enableCookieValidation' => true,
			'enableCsrfValidation' => true,
			'cookieValidationKey' => 'O1d232trde1x-M97_7QvwPo-5QGdkLMp#@#@',
			'noCsrfRoutes' => [
				'catalog/product/addreview',
				'favorite/product/remark',
				'paypal/ipn/index',
				'paypal/ipn',
			],
			*/
		],
	],

];
