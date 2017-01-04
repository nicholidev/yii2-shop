<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'order' => [
		'class' => 'fecshop\services\Order',
		'paymentStatus' => [
			'pending' => 'pending', #δ�����״̬
			'processing' => 'processing' # �Ѹ����״̬
		],
		
		'requiredAddressAttr' => [ # ����Ķ����ֶΡ�
			'first_name',
			'last_name',
			'email',
			'telephone',
			'street1',
			'country',
			'city',
			'state',
			'zip'
		],
		# �ӷ���
		'childService' => [
			
		],
	],
];
