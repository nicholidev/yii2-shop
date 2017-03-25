<?php
/**
 * FecShop file.
 * 
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'sitemap' => [
		'class' => 'fecshop\services\Sitemap',
		'sitemapConfig' => [
			/**
			 * ������������ã������ܸо��܆��£�������Ϊstore��key����store service���Ѿ����ã�
			 * Ϊʲô��Ҫ���������¸�һ���أ�  ��������Ϊ�˸��ӵ����
			 *
			 */
			# appfront���
			'appfront' => [
				# store��key(����)��
				'fecshop.appfront.fancyecommerce.com' => [
					'https'			=> false,  # false����ʹ��http��true����ʹ��https			
					'sitemapDir' 	=> '@appfront/web/sitemap.xml', # sitemap��ŵĵ�ַ
					'showScriptName'=> true,	# �Ƿ���ʾindex.php ��Ʃ��http://www.fecshop.com/index.php/xxxxxx,��nginxû��������д��������Ҫ����Ϊtrue,����url�л����index.php�������404
												# ������ö�seo��˵������Ϊfalse����ʣ�Ҳ�������� url��index.php ������������Ҫ����nginx��url��д
				],
				# store��key(����)
				'fecshop.appfront.fancyecommerce.com/fr' => [
					'https'			=> false,  # false����ʹ��http��true����ʹ��https			
					'sitemapDir' 	=> '@appfront/web/fr/sitemap.xml', # sitemap��ŵĵ�ַ
					'showScriptName'=> true,
				],
				
				'fecshop.appfront.es.fancyecommerce.com' => [
					'https'			=> false,  # false����ʹ��http��true����ʹ��https			
					'sitemapDir' 	=> '@appfront/web/sitemap_es.xml',
					'showScriptName'=> true,
				],
				'fecshop.appfront.fancyecommerce.com/cn' => [
					'https'			=> false,  # false����ʹ��http��true����ʹ��https			
					'sitemapDir' 	=> '@appfront/web/cn/sitemap.xml',
					'showScriptName'=> true,
				],
			]
		],
	],
];