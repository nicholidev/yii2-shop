<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'fecadmin' => [
        'class' => '\fecadmin\Module',
        'controllerMap' => [
            'login' => [
        		'class' => 'fecshop\app\appadmin\modules\Fecadmin\controllers\LoginController',
        	],
            'logout' => [
        		'class' => 'fecshop\app\appadmin\modules\Fecadmin\controllers\LogoutController',
        	],
            'account' => [
        		'class' => 'fecshop\app\appadmin\modules\Fecadmin\controllers\AccountController',
        	],
        	'cache' => [
        		'class' => 'fecshop\app\appadmin\modules\Fecadmin\controllers\CacheController',
        	],
            'index' => [
        		'class' => 'fecshop\app\appadmin\modules\Fecadmin\controllers\IndexController',
        	],
            'error' => [
        		'class' => 'fecshop\app\appadmin\modules\Fecadmin\controllers\ErrorController',
        	],
            'systemlog' => [
        		'class' => 'fecshop\app\appadmin\modules\Fecadmin\controllers\SystemlogController',
        	],
        ],
        'params' => [
            /**
             * Fecshop缓存是基于redis，下面是各个入口redis配置所在的文件路径
             * 1.`commonConfig`是公用部分
             * 2.app开头的key，指的是各个入口的redis所在的配置文件
             * 这个配置的作用，是为了在后台清空各个入口的全部缓存，因此需要加载相应的redis的配置
             */
            'cacheRedisConfigFile' => [
                'commonConfig'      => '@common/config/main-local.php',
                'appAdmin'           => '@appadmin/config/main-local.php',
                'appApi'            => '@appapi/config/main-local.php',
                'appFront'          => '@appfront/config/main-local.php',
                'appHtml5'          => '@apphtml5/config/main-local.php',
                'appServer'         => '@appserver/config/main-local.php',
                
            ],
        ],
    ],
];
