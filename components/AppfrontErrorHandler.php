<?php
namespace fecshop\components;

use Yii;
use yii\web\ErrorHandler;
use yii\base\Exception;

/**
 * �쳣������
 */
class AppfrontErrorHandler extends ErrorHandler
{
    /**
     * [renderException description]
     * @property  $exception | Object �쳣���ݶ���
     * 
     */
    public function renderException($exception)
    {
        //echo 1;exit;
        // ��ȡ�쳣���� , 404ҳ�治���ռ�
        $code = $exception->statusCode ?: 500; 
        if ($code != 404) {
            method_exists($exception,'getMessage')  ? $message  = $exception->getMessage()  : $message = '';
            method_exists($exception,'getName')     ? $name     = $exception->getName()     : $name = '';
            method_exists($exception,'getFile')     ? $file     = $exception->getFile()     : $file = '';
            method_exists($exception,'getLine')     ? $line     = $exception->getLine()     : $line = '';
            method_exists($exception,'getTraceAsString') ? $traceString = $exception->getTraceAsString() : $traceString = '';
            
            $time     = time();
            $ip       = Yii::$app->request->userIP;
            $url      = Yii::$service->url->getCurrentUrl();
            $req_info = $this->getRequestInfo();
            $reponse = Yii::$app->response;
            Yii::$app->response->format = $reponse::FORMAT_JSON;
            if (YII_ENV_PROD) {
                
                $errorKey = $this->saveProdException($code, $message, $file, $line, $time, $ip, $name, $traceString, $url, $req_info);
                Yii::$app->response->data = [
                    'code'      => $code,
                    'error_no'  => $errorKey,
                ];
                Yii::$app->response->send();
                Yii::$app->end();
            } else {
                $time    = date('Y-m-d H:i:s', $time);
                $exceptionInfo = [
                    'code'      => $code, 
                    'message'   => $message,
                    'file'      => $file,
                    'line'      => $line,
                    'time'      => $time,
                    'ip'        => $ip,
                    'name'      => $name,
                    'traceString' => $traceString,
                ];
                Yii::$app->response->data = $exceptionInfo;
                Yii::$app->response->send();
                Yii::$app->end();
            }
        } else {
            parent::renderException($exception);
        }
    }
    
    public function getRequestInfo(){
        $request = Yii::$app->request;
        $ajax = 0;
        $request_type = '';
        $request_data = [];
        $header_accept = '';
        $header_user_agent = '';
        if ($request->isAjax) { 
            $ajax = 1;
        }
        if ($request->isGet)  { 
            $request_type = 'get';
            $request_data = $request->get();
        }
        if ($request->isPost) { 
            $request_type = 'post';
            $request_data = $request->post();
        }
        // $headers is an object of yii\web\HeaderCollection 
        $headers = $request->getHeaders();
        $headers_arr = [];
        if (is_object($headers) or is_array($headers)) {
            foreach ($headers as $k=>$v) {
                $headers_arr[$k] = $v;
            }
        }
        $userHost   = Yii::$app->request->userHost; 
        $userIP     = Yii::$app->request->userIP;
        return [
            'ajax'              => $ajax,
            'request_type'      => $request_type,
            'request_data'      => $request_data,
            'headers_data'      => $headers_arr,
            'userHost'          => $userHost,
            'userIP'            => $userIP,
        ];
    }
        
    public function saveProdException($code, $message, $file, $line, $created_at, $ip, $name, $trace_string, $url, $req_info){
        return Yii::$service->helper->errorHandler->saveByErrorHandler(
            $code, $message, $file, $line, $created_at,
            $ip, $name, $trace_string, $url, $req_info
        );
        
    }
    /**
     * ������Ŀǰû�б�д����Ҳ������ Sentry��������־�ռ���ܣ� ���ռ�������־��
     * $option = [
     *   'fromMail' => 'xxx@xxx.com',
     *   'subject'  => 'fecshop���� Code:' . $exceptionInfo['code'],
     *   'htmlBody' => '�쳣' . '(' . YII_ENV . ')' . $exceptionInfo['code'] . ':' . $exceptionInfo['message'] . '<br />�ļ���' . $exceptionInfo['file'] . ':' . $exceptionInfo['line'] . '<br /> ʱ�䣺' . $exceptionInfo['time'] . '<br />����ip��' . $exceptionInfo['ip'],
     * ];
     *
     *
     *if (!$result) {
     *    throw new Exception("Exception mail send faild!", 1);
     *}
     */
}
