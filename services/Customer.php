<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mysqldb\customer\CustomerRegister;
use fecshop\models\mysqldb\customer\CustomerLogin;
use fecshop\models\mysqldb\Customer as CustomerModel;
/**
 * Customer service
 * @property Image|\fecshop\services\Product\Image $image ,This property is read-only.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Customer extends Service
{
	public $customer_register;
	const USER_LOGIN_SUCCESS_REDIRECT_URL_KEY = 'usr_login_success_redirect_url';
	/**
	 * 注册用户名字的最小长度
	 */
	protected function actionGetRegisterNameMinLength(){
		if(isset($this->customer_register['min_name_length'])){
			return $this->customer_register['min_name_length'];
		}
	}
	/**
	 * 注册用户名字的最大长度
	 */
	protected function actionGetRegisterNameMaxLength(){
		if(isset($this->customer_register['max_name_length'])){
			return $this->customer_register['max_name_length'];
		}
	}
	/**
	 * 注册用户密码的最小长度
	 */
	protected function actionGetRegisterPassMinLength(){
		if(isset($this->customer_register['min_pass_length'])){
			return $this->customer_register['min_pass_length'];
		}
	}
	/**
	 * 注册用户密码的最大长度
	 */
	protected function actionGetRegisterPassMaxLength(){
		if(isset($this->customer_register['max_pass_length'])){
			return $this->customer_register['max_pass_length'];
		}
	}
	
	/**
	 * @property $data|Array
	 * like :['email'=>'xxx@xxx.com','password'=>'xxxx']
	 */
	protected function actionLogin($data){
		
		$model = new CustomerLogin;
		$model->email 	 = $data['email'];
		$model->password = $data['password'];
		$loginStatus = $model->login();
		$errors = $model->errors;
		//var_dump($errors);
		//exit;
		Yii::$service->helper->errors->add($errors);
		return $loginStatus;
	}
	/**
	 * @property $data|Array
	 * register customer account
	 * ['email','firstname','lastname','password'
	 *	
	 * ]
	 */
	protected function actionRegister($param){
		$model = new CustomerRegister;
		$model->attributes = $param;
		if($model->validate()){
			$model->created_at = time();
			$model->updated_at = time();
			return $model->save();
		}else{
			$errors = $model->errors;
			Yii::$service->helper->errors->add($errors);
			return false;;
		}
	}
	
	/**
	 * @property $customerId|Int
	 * Get customer info by customerId, if customer id is empty, current customer id will be set, 
	 * if current customer id is empty , false will be return .
	 */
	protected function actionViewInfo($customerId = ''){
		
		
	}
	
	/**
	 * @property $password|String
	 * @property $customerId|Int or String or Object
	 * change  customer password.
	 * if $customer id is empty, it will be equals current customer id.
	 */ 
	protected function actionChangePassword($password,$identity){
		if(is_int($identity)){
			$customer_id = $identity;
			$customerModel = CustomerModel::findIdentity($customer_id);
		}else if(is_string($identity)){
			$email = $identity;
			$customerModel = CustomerModel::findByEmail($email);
		}else if(is_object($identity)){
			$customerModel = $identity;
		}
		$customerModel->updated_at = time();
		$customerModel->setPassword($password);
		$customerModel->save();
	}
	/**
	 * @property $password|String
	 * @property $customerId|Int or String or Object
	 * change  customer password.
	 * 更改密码，然后，清空token
	 */
	protected function actionChangePasswordAndClearToken($password,$identity){
		if(is_int($identity)){
			$customer_id = $identity;
			$customerModel = CustomerModel::findIdentity($customer_id);
		}else if(is_string($identity)){
			$email = $identity;
			$customerModel = CustomerModel::findByEmail($email);
		}else if(is_object($identity)){
			$customerModel = $identity;
		}else{
			Yii::$service->helper->errors->add('identity is not right');
			return;
		}
		//echo $password;exit;
		$customerModel->setPassword($password);
		$customerModel->removePasswordResetToken();
		$customerModel->updated_at = time();
		$customerModel->save();
		return true;
	}
	
	/**
	 * @property $customerId|Array
	 * ['firstname','lastname','password','customerId']
	 */
	protected function actionChangeNameAndPassword($data){
		
	}
	
	/**
	 * get current customer identify.
	 */
	protected function actionGetCurrentAccount(){
		return Yii::$app->user->identity->username;
		
	}
	/**
	 * get CustomerModel by Email address
	 */
	protected function actionGetUserIdentityByEmail($email){
		$one = CustomerModel::findByEmail($email);
		if($one['email']){
			return $one;
		}else{
			return false;
		}
	}
	
	/**
	 * @property $identify|object(customer object) or String
	 * @return 生成的resetToken，如果生成失败返回false
	 * 用来找回密码，生成resetToken，返回
	 */
	protected function actionGeneratePasswordResetToken($identify){
		if(is_string($identify)){
			$email = $identify;
			$one = $this->getUserIdentityByEmail($email);
		}else{
			$one = $identify;
		}
		if($one){
			
			$one->generatePasswordResetToken();
			$one->updated_at = time();
			$one->save();
			return $one->password_reset_token;
		}
		return false;
	}
	
	/**
	 * 通过PasswordResetToken 得到user
	 */
	protected function actionFindByPasswordResetToken($token){
		return CustomerModel::findByPasswordResetToken($token);
	}
	/**
	 * @property $url|String
	 * 在一些功能中，需要用户进行登录操作，等用户操作成功后，应该跳转到相应的页面中，这里通过session存储需要跳转到的url。
	 * 某些页面 ， 譬如评论页面，需要用户登录后才能进行登录操作，那么可以通过这个方法把url set 进去，登录成功
	 * 后，页面不会跳转到账户中心，而是需要操作的页面中。
	 */
	protected function actionSetLoginSuccessRedirectUrl($url){
		return Yii::$app->session->set($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY,$url);
	}
	/**
	 * @property $url|String
	 * 在一些功能中，需要用户进行登录操作，等用户操作成功后，应该跳转到相应的页面中，这里通过session得到需要跳转到的url。
	 */
	protected function actionGetLoginSuccessRedirectUrl(){
		$url = Yii::$app->session->get($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY);
		return $url ? $url : '';
	}
	
	protected  function actionLoginSuccessRedirect($urlKey){
		$url = $this->getLoginSuccessRedirectUrl();
		
		if($url){
			# 这个优先级最高
			# 在跳转之前，去掉这个session存储的值。跳转后，这个值必须失效。
			Yii::$app->session->remove($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY);
			//echo Yii::$app->session->get($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY);
			//exit;
			Yii::$service->url->redirect($url);
			
		}else{
			Yii::$service->url->redirectByUrlKey($urlKey);
		}
	}
	/**
	 * 得到status为删除状态的值
	 */ 
	protected function actionGetStatusDeleted(){
		return CustomerModel::STATUS_DELETED;
	}
	/**
	 * 得到status为激活状态的值
	 */ 
	protected function actionGetStatusActive(){
		return CustomerModel::STATUS_ACTIVE;
	}
	
	protected function actionGetPrimaryKey(){
		return 'id';
	}
	
	public function coll($filter=''){
		$query = CustomerModel::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		//var_dump($query->all());exit;
		return [
			'coll' => $query->all(),
			'count'=> $query->count(),
		];
	}
	
}