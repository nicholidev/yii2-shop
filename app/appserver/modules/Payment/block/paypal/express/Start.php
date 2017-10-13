<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment\block\paypal\express;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Start
{
    
    public $_errors;
    
    public function startExpress()
    {
        $checkStatus = $this->checkStockQty();
        if(!$checkStatus){
            return [
                'code'      => 401,
                'content'   => $this->_errors,
            ];
        }
        $methodName_ = 'SetExpressCheckout';
        $nvpStr_ = Yii::$service->payment->paypal->getExpressTokenNvpStr();
        //echo $nvpStr_;exit;
        $SetExpressCheckoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
        //var_dump($SetExpressCheckoutReturn);
        if (strtolower($SetExpressCheckoutReturn['ACK']) == 'success') {
            $token = $SetExpressCheckoutReturn['TOKEN'];
            # ���ɶ�����������ֻ��id,increment_id,token �����ֶ���ֵ��
            if($token){
                if(!Yii::$service->order->generatePPExpressOrder($token)){
                    return [
                        'code' => 402,
                        'content' => 'generate order fail',
                    ];
                }
                $redirectUrl = Yii::$service->payment->paypal->getSetExpressCheckoutUrl($token);
                return [
                    'code' => 200,
                    'content' => $redirectUrl,
                ];
            }
        } elseif (strtolower($SetExpressCheckoutReturn['ACK']) == 'failure') {
            return [
                'code' => 403,
                'content' => $SetExpressCheckoutReturn['L_LONGMESSAGE0'],
            ];
            echo $SetExpressCheckoutReturn['L_LONGMESSAGE0'];
        } else {
            return [
                'code' => 403,
                'content' => $SetExpressCheckoutReturn,
            ];
        }
    }

    // ��鹺�ﳵ�в�Ʒ�Ŀ�档�˲�ֻ�ǳ�����飬�ڿ��֧����ɷ�����վ��ʱ�����ɶ�����ʱ�򣬻�Ҫ��һ������Ʒ��棬
    // ��Ϊ��֧���Ĺ����У���Ʒ���ܱ����ߡ�
    public function checkStockQty(){
        $stockCheck = Yii::$service->product->stock->checkItemsQty();
        
        //var_dump($stockCheck);exit;
        if(!$stockCheck){
            //Yii::$service->url->redirectByUrlKey('checkout/cart');
            $this->_errors .= 'cart products is empty';
            return false;
        }else{
            if(isset($stockCheck['stockStatus'])){
                if($stockCheck['stockStatus'] == 2){
                    $outStockProducts = $stockCheck['outStockProducts'];
                    if(is_array($outStockProducts) && !empty($outStockProducts)){
                        foreach($outStockProducts as $outStockProduct){
                            $product_name = Yii::$service->store->getStoreAttrVal($outStockProduct['product_name'], 'name');
                            $this->_errors .= 'product: ['.$product_name.'] is stock out.';
                        }
                        
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
}
