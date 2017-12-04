<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'cms' => [
        'class' => 'fecshop\services\Cms',
        // 子服务
        'childService' => [
            'article' => [
                'class'            => 'fecshop\services\cms\Article',
                'storage' => 'ArticleMysqldb', // ArticleMysqldb or ArticleMongodb.
            ],

            'staticblock' => [
                'class'    => 'fecshop\services\cms\StaticBlock',
                'storage'    => 'StaticBlockMongodb', // mysqldb or mongodb.
            ],
        ],
    ],
];
