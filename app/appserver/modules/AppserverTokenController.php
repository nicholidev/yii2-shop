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
use yii\rest\Controller;
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
class AppserverTokenController extends Controller
{
    
    public $enableCsrfValidation = false ;
    
    public function init()
    {
        parent::init();
        // \Yii::$app->user->enableSession = false;
    }
    
    public function behaviors()  
    {  
        $behaviors = parent::behaviors();  
        $fecshop_uuid = Yii::$service->session->fecshop_uuid;
        $cors_allow_headers = [$fecshop_uuid,'fecshop-lang','fecshop-currency','access-token'];
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors["corsFilter"] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Headers' => $cors_allow_headers,
                // Allow only headers 'X-Wsse'
                //'Access-Control-Allow-Credentials' => null,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 86400,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => $cors_allow_headers,
            ],
        ];
        
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
    
    
    
    /**
     * get current block
     * you can change $this->blockNamespace.
     */
    public function getBlock($blockName = '')
    {
        if (!$blockName) {
            $blockName = $this->action->id;
        }
        if (!$this->blockNamespace) {
            $this->blockNamespace = Yii::$app->controller->module->blockNamespace;
        }
        if (!$this->blockNamespace) {
            throw new \yii\web\HttpException(406, 'blockNamespace is empty , you should config it in module->blockNamespace or controller blockNamespace ');
        }
        $viewId = $this->id;
        $viewId = str_replace('/', '\\', $viewId);
        $relativeFile = '\\'.$this->blockNamespace;
        $relativeFile .= '\\'.$viewId.'\\'.ucfirst($blockName);
        //�����Ƿ���rewriteMap�д�����д
        $relativeFile = Yii::mapGetName($relativeFile);
        
        return new $relativeFile();
    }
    

}
