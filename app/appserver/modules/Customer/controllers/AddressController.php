<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AddressController extends AppserverTokenController
{
    public $enableCsrfValidation = false ;
    /**
     * 登录用户的部分
     */
    public function actionIndex(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'addressList' => $this->coll(),
        ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
        
    }
    
    
    public function actionEdit(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $address = [];
        $country = '';
        $address_id = Yii::$app->request->get('address_id');
        if($address_id){
            $addressModel = Yii::$service->customer->address->getByPrimaryKey($address_id);
            $identity = Yii::$app->user->identity;
            $customer_id = $identity['id'];
            
            if ($addressModel['address_id']) {
                // 该id必须是当前用户的
                if ($customer_id == $addressModel['customer_id']) {
                    foreach ($addressModel as $k=>$v) {
                        $address[$k] = $v;
                    }
                }
            }
            $country = isset($address['country']) ? $address['country'] : '';
            
        }else{
            
        }
        if(!$country){
            $country = Yii::$service->helper->country->getDefaultCountry();
        }
        $countryArr = Yii::$service->helper->country->getAllCountryArray();
        $address['countryArr'] = $countryArr;
        $state = isset($address['state']) ? $address['state'] : '';
        $stateArr = Yii::$service->helper->country->getStateByContryCode($country);
        $stateIsSelect = 0;
        if(!empty($stateArr)){
            $stateIsSelect = 1;
        }
        $address['stateArr'] = $stateArr;
        $address['stateIsSelect'] = $stateIsSelect;
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'address' => $address,
        ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
        
    }
    
    
    
    public function actionRemove(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $address_id = Yii::$app->request->post('address_id');
        if($address_id){
            $this->removeAddressById($address_id);
            
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }else{
            $code = Yii::$service->helper->appserver->account_address_is_not_exist;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
        }
    }
    
    public function removeAddressById($address_id)
    {
        $identity = Yii::$app->user->identity;
        $customer_id = $identity['id'];
        Yii::$service->customer->address->remove($address_id, $customer_id);
    }
    
    
    public function coll()
    {
        $identity = Yii::$app->user->identity;
        $customer_id = $identity['id'];
        $filter = [
                'numPerPage'    => 100,
                'pageNum'        => 1,
                'orderBy'    => ['updated_at' => SORT_DESC],
                'where'            => [
                    ['customer_id' => $customer_id],
                ],
            'asArray' => true,
          ];
        $coll = Yii::$service->customer->address->coll($filter);
        $arr = [];
        if (isset($coll['coll']) && !empty($coll['coll'])) {
            foreach($coll['coll'] as $one){
                $one['stateName'] = Yii::$service->helper->country->getStateByContryCode($one['country'],$one['state']);
                $one['countryName'] = Yii::$service->helper->country->getCountryNameByKey($one['country']); 
                $arr[] = $one;
            }
        }
        return $arr;
    }
    
    public function actionChangecountry()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $country = Yii::$app->request->get('country');
        if($country){
           $stateArr = Yii::$service->helper->country->getStateByContryCode($country);
           $stateIsSelect = 0;
            if(!empty($stateArr)){
                $stateIsSelect = 1;
            }
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'stateIsSelect' => $stateIsSelect,
                'stateArr' => $stateArr,
            ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
    }
    
    public function actionSave(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $address_id         = Yii::$app->request->post('address_id'); 
        $first_name         = Yii::$app->request->post('first_name'); 
        $last_name          = Yii::$app->request->post('last_name'); 
        $email              = Yii::$app->request->post('email'); 
        $telephone          = Yii::$app->request->post('telephone'); 
        $addressCountry     = Yii::$app->request->post('addressCountry'); 
        $addressState       = Yii::$app->request->post('addressState'); 
        $city               = Yii::$app->request->post('city'); 
        $street1            = Yii::$app->request->post('street1'); 
        $street2            = Yii::$app->request->post('street2'); 
        $zip                = Yii::$app->request->post('zip'); 
        $isDefaultActive    = Yii::$app->request->post('isDefaultActive'); 
        if($address_id){
            $addressModel = Yii::$service->customer->address->getByPrimaryKey($address_id);
            $identity = Yii::$app->user->identity;
            $customer_id = $identity['id'];
            if ($customer_id != $addressModel['customer_id']) {
                $code = Yii::$service->helper->appserver->account_address_is_not_exist;
                $data = [];
                $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
                
                return $reponseData;
            }
        }
        // 地址信息
        $address = [
            'address_id' => $address_id,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'country'    => $addressCountry,
            'state'      => $addressState,
            'telephone'  => $telephone,
            'city'       => $city,
            'street1'    => $street1,
            'street2'    => $street2,
            'zip'        => $zip,
            'is_default' => $isDefaultActive,
        ];
        $addressInfo = \Yii::$service->helper->htmlEncode($address);
        $identity = Yii::$app->user->identity;
        $addressInfo['customer_id'] = $identity['id'];
        $saveStatus = Yii::$service->customer->address->save($addressInfo);
        if (!$saveStatus) {
            $code = Yii::$service->helper->appserver->account_address_save_fail;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [ ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
    }
    
   
}