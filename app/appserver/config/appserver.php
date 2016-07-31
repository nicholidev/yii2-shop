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
		//'appfrontBaseTheme' 	=> '@fecshop/app/appfront/theme/base/front',
		//'appfrontBaseLayoutName'=> 'main.php',
	],
	# language config.
	'components' => [
		'i18n' => [
			'translations' => [
				'appserver' => [
					//'class' => 'yii\i18n\PhpMessageSource',
					'class' => 'fecshop\yii\i18n\PhpMessageSource',
					'basePaths' => [
						'@fecshop/app/appserver/languages',
						'@appserver/languages',
					],
					'sourceLanguage' => 'en_US', # ��� en_US Ҳ�뷭�룬��ô���Ըĳ�en_XX��
				],
			],
		],
		'assetManager' => [
			'forceCopy' => true,
		],
		'user' => [
			'identityClass' => 'fecadmin\models\AdminUser',
			'enableAutoLogin' => true,
		],
		
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		
		
		
		'urlManager' => [
			'class' => 'yii\web\UrlManager',
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				'' => 'cms/home/index',
			],
			//'baseUrl' => '/fr/',
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
