<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ForgotController extends AppserverController
{
    
    public function actionPassword()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        //$identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        //if($identity['id']){
        // 用户已经登录
        //    $code = Yii::$service->helper->appserver->account_is_logined;
        //    $data = [];
        //    $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        //    
        //    return $reponseData;
        //}
        $forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        $forgotCaptchaActive = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;

        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'forgotCaptchaActive' => $forgotCaptchaActive,
        ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
        
    }
    
    public function actionResetpassword(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $resetToken = Yii::$app->request->get('resetToken');
        $identity = Yii::$service->customer->findByPasswordResetToken($resetToken);
        //var_dump($identity );exit;
        if ($identity) {
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'resetPasswordActive' => true,
            ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        } else {
            
            $code = Yii::$service->helper->appserver->account_forget_password_token_timeout;
            $data = [
                'resetPasswordActive' => false,
            ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        
        
        
    }
    
    public function actionSubmitresetpassword(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $resetToken = Yii::$app->request->post('resetToken');
        $identity = Yii::$service->customer->findByPasswordResetToken($resetToken);
        //var_dump($identity );exit;
        if (!$identity) {
            $code = Yii::$service->helper->appserver->account_forget_password_token_timeout;
            $data = [
                'resetPasswordActive' => false,
            ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('newPassword');
        $confirmation = Yii::$app->request->post('confirmPassword');
        $errorArr = [];
        if (!$email || !$password || !$confirmation) {
            $errorArr [] = 'email or password can not empty';
        }
        if ($password != $confirmation) {
            $errorArr [] = 'new password and confirmation password must be consistent';
        }
        if ($identity['email'] != $email) {
            $errorArr [] = 'email do not match the resetToken';
        }
        if(!empty($errorArr)){
            $data = [
                'error' => implode(',',$errorArr),
            ];
            $code = Yii::$service->helper->appserver->account_forget_password_reset_param_invalid;
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        
        $status = Yii::$service->customer->changePasswordAndClearToken($password, $identity);
        if ($status) {
            $code = Yii::$service->helper->appserver->status_success;
            $data = [ ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }else{
            $code = Yii::$service->helper->appserver->account_forget_password_reset_fail;
            $data = [ ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        
    }
    
    public function actionSendcode()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity['id']){
            // 用户已经登录
            $code = Yii::$service->helper->appserver->account_is_logined;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        
        $email       = Yii::$app->request->post('email');
        $forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        $forgotCaptchaActive = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;
        if($forgotCaptchaActive){
            $captcha    = Yii::$app->request->post('captcha');
            if(!Yii::$service->helper->captcha->validateCaptcha($captcha)){
                $code = Yii::$service->helper->appserver->status_invalid_captcha;
                $data = [];
                $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
                
                return $reponseData;
            }
        }
        // 验证邮箱是否存在
        $identity = Yii::$service->customer->getUserIdentityByEmail($email);
        if(!$identity){
            $code = Yii::$service->helper->appserver->account_email_not_exist;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        // 发送邮件
        $domain       = Yii::$app->request->post('domain');
        $domain = trim($domain,'/').'/';
        //echo $domain;exit;
        Yii::$service->helper->setAppServiceDomain($domain);
        $passwordResetToken = Yii::$service->customer->generatePasswordResetToken($identity);
        $identity['password_reset_token'] = $passwordResetToken;
        $this->sendForgotPasswordEmail($identity);

        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
    }
    /**
     * 发送忘记密码邮件.
     */
    protected function sendForgotPasswordEmail($identity)
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if ($identity) {
            Yii::$service->email->customer->sendForgotPasswordEmail($identity);
        }
    }

}