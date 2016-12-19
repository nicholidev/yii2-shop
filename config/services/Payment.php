<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'payment' => [
		'class' => 'fecshop\services\Payment',
		'paymentConfig' => [
			'standard' => [
				'paypal_standard' => [
					'label' => 'PayPal Website Payments Standard',
					'image' => ['images/paypal_standard.png','common'], # ֧��ҳ����ʾ��ͼƬ��
					'supplement' => 'You will be redirected to the PayPal website when you place an order. ', # ����
					
				],
				'credit_card' => [
					'label' => 'Credit Card',
					'image' => ['images/mastercard.png','common'] ,# ֧��ҳ����ʾ��ͼƬ��
					'supplement' => '', # ����
					'style'	=> '<style></style>',  # ����css
				
				],
			],
			
		]
	]
];