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
				'check_money' => [
					'label' 				=> 'Check / Money Order',
					//'image' => ['images/mastercard.png','common'] ,# ֧��ҳ����ʾ��ͼƬ��
					'supplement' 			=> 'Off-line Money Payments', # ����
					'style'					=> '<style></style>',  # ����css
					'start_url' 			=> '@homeUrl/payment/checkmoney/start',
					'success_redirect_url' 	=> '@homeUrl/payment/checkmoney/success',
				],
				'paypal_standard' => [
					'label' 				=> 'PayPal Website Payments Standard',
					'image' 				=> ['images/paypal_standard.png','common'], # ֧��ҳ����ʾ��ͼƬ��
					'supplement' 			=> 'You will be redirected to the PayPal website when you place an order. ', # ����
					'start_url' 			=> '@homeUrl/payment/paypal/standard/start',
					'IPN_url' 				=> '@homeUrl/payment/paypal/standard/ipn',
					'success_redirect_url' 	=> '@homeUrl/payment/paypal/standard/success',
				],
			],
			
			'express' => [
			
			],
			
		]
	]
];