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

/**
 * AdminUser services. 用来给后台的用户提供数据。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AdminUser extends Service
{
    
    protected $_adminUserLoginModelName = '\fecshop\models\mysqldb\adminUser\AdminUserLogin';
    protected $_adminUserLoginModel;
    
    
     public function init(){
        parent::init();
        list($this->_adminUserLoginModelName,$this->_adminUserLoginModel) = \Yii::mapGet($this->_adminUserLoginModelName);  
         
    }
    /**
     * @property $ids | Int Array
     * @return 得到相应用户的数组。
     */
    protected function actionGetIdAndNameArrByIds($ids)
    {
        $user_coll = \fecadmin\models\AdminUser::find()->asArray()->select(['id', 'username'])->where([
            'in', 'id', $ids,
        ])->all();
        $users = [];
        foreach ($user_coll as $one) {
            $users[$one['id']] = $one['username'];
        }

        return $users;
    }
    
    /** AppServer 部分使用的函数
     * @property $email | String
     * @property $password | String
     * 无状态登录，通过email 和password进行登录
     * 登录成功后，合并购物车，返回accessToken
     * ** 该函数是未登录用户，通过参数进行登录需要执行的函数。
     */
    protected function actionLoginAndGetAccessToken($username,$password){
        $header = Yii::$app->request->getHeaders();
        if(isset($header['access-token']) && $header['access-token']){
            $accessToken = $header['access-token'];
        }   
        // 如果request header中有access-token，则查看这个 access-token 是否有效
        if($accessToken){
            $identity = Yii::$app->user->loginByAccessToken($accessToken);
            if ($identity !== null) {
                $access_token_created_at = $identity->access_token_created_at;
                $timeout = Yii::$service->session->timeout;
                if($access_token_created_at + $timeout > time()){
                    return $accessToken;
                } 
            }
        }
        // 如果上面access-token不存在
        $data = [
            'username'     => $username,
            'password'  => $password,
        ];
        
        if($this->login($data)){
            $identity = Yii::$app->user->identity;
            $identity->generateAccessToken();
            $identity->access_token_created_at = time();
            $identity->save();
            $this->setHeaderAccessToken($identity->access_token);
            return $identity->access_token;
            
        }
    }
    
    
    /**
     * @property $data|array
     * 数组格式：['username'=>'xxx@xxx.com','password'=>'xxxx']
     */
    protected function actionLogin($data)
    {
        $model = new $this->_adminUserLoginModelName();
        $model->username    = $data['username'];
        $model->password    = $data['password'];
        //echo 1;exit;
        $loginStatus        = $model->login();
        $errors             = $model->errors;
        if (!empty($errors)) {
            Yii::$service->helper->errors->addByModelErrors($errors);
        }

        return $loginStatus;
    }
    
    protected function actionSetHeaderAccessToken($accessToken){
        if($accessToken){
            Yii::$app->response->getHeaders()->set('access-token',$accessToken);
            return true;
        }
    }
    
    

    /** AppServer 部分使用的函数
     * @property $type | null or  Object
     * 从request headers中获取access-token，然后执行登录
     * 如果登录成功，然后验证时间是否过期
     * 如果不过期，则返回identity
     * ** 该方法为appserver用户通过access-token验证需要执行的函数。
     */
    protected function actionLoginByAccessToken($type = null){
        $header = Yii::$app->request->getHeaders();
        if(isset($header['access-token']) && $header['access-token']){
            $accessToken = $header['access-token'];
        }
        if($accessToken){
            $identity = Yii::$app->user->loginByAccessToken($accessToken, $type);
            if ($identity !== null) {
                $access_token_created_at = $identity->access_token_created_at;
                $timeout = Yii::$service->session->timeout;
                // 如果时间没有过期，则返回identity
                if($access_token_created_at + $timeout > time()){
                    //如果时间没有过期，但是快要过期了，在过$updateTimeLimit段时间就要过期，那么更新access_token_created_at。
                    $updateTimeLimit = Yii::$service->session->updateTimeLimit;
                    if($access_token_created_at + $timeout <= (time() + $updateTimeLimit )){
                        $identity->access_token_created_at = time();
                        $identity->save();
                    }
                    return $identity;
                }else{
                    $this->logoutByAccessToken();
                    return false;
                }
            }
        }
    }
    
    /**
     * 通过accessToek的方式，进行登出从操作。
     */
    public function logoutByAccessToken()
    {
        $userComponent = Yii::$app->user;
        $identity = $userComponent->identity;
        if ($identity !== null ) {
            if(!Yii::$app->user->isGuest){
                $identity->access_token = null;
                $identity->access_token_created_at = null;
                $identity->save();
            }
            $userComponent->switchIdentity(null);
        }

        return $userComponent->getIsGuest();
    }
}
