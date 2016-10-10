<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Customer\block\account;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Forgotpassword {
	
	public function getLastData(){
		$forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
		$forgotCaptcha = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;
		
		return [
			'forgotCaptcha' => $forgotCaptcha,
		];
	}
	
	public function sendForgotPasswordMailer($editForm){
		$captcha = $editForm['captcha'];
		$forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
		$forgotCaptcha = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;
		# �����������֤�룬������֤����֤����ȷ�ͱ����ء�
		if($forgotCaptcha && !$captcha){
			Yii::$service->page->message->addError(['Captcha can not empty']);
			return;
		}else if($captcha && $forgotCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)){
			Yii::$service->page->message->addError(['Captcha is not right']);
			return;
		}
		#�жϸ������Ƿ����
		if($identity = $this->getUserIdentity($editForm)){
			# ������������� passwordResetToken
			
			$passwordResetToken = Yii::$service->customer->generatePasswordResetToken($identity);
			
			if($passwordResetToken){
				$identity['password_reset_token'] = $passwordResetToken;
				$this->sendForgotPasswordEmail($identity);
				return $identity;
			}
			
		}else{
			Yii::$service->page->message->addError(['email is not exist']);
			return;
		}
		
		
	}
	
	
	/**
	 * �������������ʼ�
	 */
	public function sendForgotPasswordEmail($identity){
		if($identity){
			$mailerConfig = Yii::$app->params['mailer'];
			$mailer_class = isset($mailerConfig['mailer_class']) ? $mailerConfig['mailer_class'] : '';
			if($mailer_class){
				
				forward_static_call(
					[$mailer_class , 'sendForgotPasswordEmail'],
					$identity
				);
			}
		}
	}
	
	public function getUserIdentity($editForm){
		$email = $editForm['email'];
		if($email){
			$identity = Yii::$service->customer->getUserIdentityByEmail($email);
			if($identity){
				return $identity;
			}
		}
		return false;
	}
}



