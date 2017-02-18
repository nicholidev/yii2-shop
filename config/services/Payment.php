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
					# ѡ��֧���󣬽��뵽��Ӧ֧��ҳ���startҳ�档
					'start_url' 			=> '@homeUrl/payment/paypal/standard/start',
					# ����IPN��Ϣ��ҳ�档
					'IPN_url' 				=> '@homeUrl/payment/paypal/standard/ipn',
					# �ڵ�����֧���ɹ�����ת����վ��ҳ��
					'success_redirect_url' 	=> '@homeUrl/payment/paypal/standard/success',
					# ����paypal֧��ҳ�棬���ȡ��������վ��ҳ�档
					'cancel_url'			=> '@homeUrl/payment/paypal/standard/cancel',
					
					# ������֧����վ��url
					'payment_url'=>'https://www.sandbox.paypal.com/cgi-bin/webscr',
					# �û���
					'user' => 'zqy234api1-facilitator@126.com',
					# �˺�
					'account'=> 'zqy234api1-facilitator@126.com',
					# ����
					'password'=>'HF4TNTTXUD6YQREH',
					# ǩ��
					'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
					
						
					//'info'		=> [
				
						//'title'=>'PayPal Website Payments Standard',
						//'enable'=> 1,
						
						//'label'=>'PayPal Website Payments Standard',
						//'description'=>'You will be redirected to the PayPal website when you place an order.',
						//'image'=> 'images/hm.png',
			
			
					//],
				],
			],
			
			'express' => [
				'paypal_express' =>[
					'nvp_url' => 'https://api-3t.sandbox.paypal.com/nvp',
					'api_url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
					'account'=> 'zqy234api1-facilitator_api1.126.com',
					'password'=>'HF4TNTTXUD6YQREH',
					'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
					
					'enable'=> 1,
					'label'=>'PayPal Express Payments',
				],
			],
			
		]
	]
];


/*
 'payment_method'=>[
		'merchant_country' => 'US',
		'paypal'=>[
			'payments_standard'=>[
				'title'=>'PayPal Website Payments Standard',
				'enable'=> 1,
				'user' => 'zqy234api1-facilitator@126.com',
				'redirect_url'=>'https://www.sandbox.paypal.com/cgi-bin/webscr',
				'account'=> 'zqy234api1-facilitator@126.com',
				'password'=>'HF4TNTTXUD6YQREH',
				'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
				
				'label'=>'PayPal Website Payments Standard',
				'description'=>'You will be redirected to the PayPal website when you place an order.',
				'image'=> 'images/hm.png',
			],
			'express_checkout' =>[
				
				'nvp_url' => 'https://api-3t.sandbox.paypal.com/nvp',
				'api_url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
				'account'=> 'zqy234api1-facilitator_api1.126.com',
				'password'=>'HF4TNTTXUD6YQREH',
				'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
				
				'enable'=> 1,
				'label'=>'PayPal Express Payments',
			],
		],
	],
*/