<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules;

use fec\controllers\FecController;
use fec\helpers\CConfig;
use Yii;
use yii\base\InvalidValueException;
use yii\filters\auth\CompositeAuth;  
use yii\filters\auth\HttpBasicAuth;  
use yii\filters\auth\HttpBearerAuth;  
use fecshop\yii\filters\auth\QueryParamAuth;  
use yii\filters\RateLimiter; 

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppserverTokenController extends AppserverController
{
    
    public $enableCsrfValidation = false ;
    
    public function behaviors()  
    {  
        $behaviors = parent::behaviors();  
        $behaviors['authenticator'] = [  
            'class' => CompositeAuth::className(),  
            'authMethods' => [  
                # ������������֤access_token��ʽ  
                //HttpBasicAuth::className(),  
                //HttpBearerAuth::className(),  
                # ����GET������֤�ķ�ʽ  
                # http://10.10.10.252:600/user/index/index?access-token=xxxxxxxxxxxxxxxxxxxx  
                QueryParamAuth::className(),  
            ],  
          
        ];  
          
        # rate limit���֣��ٶȵ���������  
        #   \myapp\code\core\Erp\User\models\User::getRateLimit($request, $action){  
        /*  �ٷ��ĵ���  
            ���������Ʊ����Ĭ�������ÿ����Ӧ����������HTTPͷ���� Ŀǰ������������Ϣ��  
            X-Rate-Limit-Limit: ͬһ��ʱ��������������������Ŀ;  
            X-Rate-Limit-Remaining: �ڵ�ǰʱ�����ʣ������������;  
            X-Rate-Limit-Reset: Ϊ�˵õ�������������ȴ���������  
            ����Խ�����Щͷ��Ϣͨ������ yii\filters\RateLimiter::enableRateLimitHeaders Ϊfalse, ����������Ĵ���ʾ����ʾ��  
  
        */  
        $rateLimit = Yii::$app->params['rateLimit'];
        if(isset($rateLimit['enable']) && $rateLimit['enable']){
            $behaviors['rateLimiter'] = [  
                'class' => RateLimiter::className(),  
                'enableRateLimitHeaders' => true,  
            ]; 
        }
        return $behaviors;  
    }  
    
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }
    
    
    

}
