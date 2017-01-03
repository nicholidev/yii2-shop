<?php
namespace fecshop\app\appfront\modules\checkout\block\onepage;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
class Placeorder{
	
	public $_check_error;
	public $_billing;
	public $_shipping_method;
	public $_payment_method;
	
	public function getLastData(){
		$post = Yii::$app->request->post();
		if(is_array($post) && !empty($post)){
			if($this->checkOrderInfo($post)){
				
			}
		}
		
		return [
		
		];
	}
	

	//$create_account = isset($billing['create_account']) ? $billing['create_account'] : '';
	//$customer_password 	= isset($billing['customer_password']) ? $billing['customer_password'] : '';
	//$confirm_password 	= isset($billing['confirm_password']) ? $billing['confirm_password'] : '';

	public function checkOrderInfo($post){
		
		$address_one = '';
		$address_id = isset($post['address_id']) ? $post['address_id'] : '';
		$billing = isset($post['billing']) ? $post['billing'] : '';
		
		if($billing && is_array($billing)){
			# ���address�ı�д�ֶ��Ƿ񶼴���
			if(!Yii::$service->order->checkRequiredAddressAttr($billing)){
				$this->_check_error[] = Yii::$service->helper->errors->get();
				return ;
			}
			$this->_billing = $billing;
		}else if($address_id){
			if(Yii::$app->user->isGuest){
				$this->_check_error[] = 'address id can not use for guest';
				return false; # address_id ��������������ǵ�¼�û���
			}else{
				$customer_id = Yii::$app->user->identity->id;
				if(!$customer_id){
					$this->_check_error[] = 'customer id is empty';
					return false;
				}else{
					$address_one = Yii::$service->customer->address->getAddressByIdAndCustomerId($address_id,$customer_id);
					if(!$address_one){
						$this->_check_error[] = 'current address id is not belong to current user';
						return false;
					}else{
						# ��address_id��ȡ�������ֶΣ��鿴�Ƿ������д��Ҫ��
						if(!Yii::$service->order->checkRequiredAddressAttr($address_one)){
							$this->_check_error[] = Yii::$service->helper->errors->get();
							return ;
						}
						$this->_billing = $address_one;
					}
				}
			}	
		}
		$shipping_method= isset($billing['shipping_method']) ? $billing['shipping_method'] : '';
		$payment_method = isset($billing['payment_method']) ? $billing['payment_method'] : '';
		# ��֤���˷�ʽ
		if(!$shipping_method){
			$this->_check_error[] = 'shipping method can not empty';
			return ;
		}else{
			if(!Yii::$service->shipping->ifIsCorrect($shipping_method)){
				$this->_check_error[] = 'shipping method is not correct';
				return ;
			}
		}
		# ��֤���ѷ�ʽ
		if(!$payment_method){
			$this->_check_error[] = 'payment method can not empty';
			return ;
		}
		$this->_shipping_method = $shipping_method;
		$this->_payment_method = $payment_method;
		return true;
	}
	
	
	
	
}