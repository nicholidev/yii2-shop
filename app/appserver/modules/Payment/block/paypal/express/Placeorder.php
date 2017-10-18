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
class Placeorder
{
    /**
     * �û����˵���ַ��Ϣ��ͨ���û����ݵ���Ϣ���������
     */
    public $_billing;

    public $_address_id;
    /**
     * �û��Ļ��˷�ʽ.
     */
    public $_shipping_method;
    /**
     * �û���֧����ʽ.
     */
    public $_payment_method;

    public function getLastData()
    {
        $post = Yii::$app->request->post();
        $token = Yii::$app->request->post('token');
        if(!$token){
            
            return [
                'code' => 401,
                'content' => 'token can not empty',
            ];
        }
        if (is_array($post) && !empty($post)) {
            $post = \Yii::$service->helper->htmlEncode($post);
            // ����paypal���֧��
            $post['payment_method'] = Yii::$service->payment->paypal->express_payment_method;
            // ���ǰ̨���ݵ����ݵ�������
            // ���ǰ̨���ݵ����ݵ�����
            $checkInfo = $this->checkOrderInfoAndInit($post);
            if ($checkInfo !== true) {
                return $checkInfo;
            }
            
            // ����ο��û���ѡ��ע���˺ţ���ע�ᣬ��¼�����ѵ�ַд�뵽�û���address��
            $save_address_status = $this->updateAddress($post);
           
            // ����Cart��Ϣ
            //$this->updateCart();
            // ����checkout type
            $serviceOrder = Yii::$service->order;
            $checkout_type = $serviceOrder::CHECKOUT_TYPE_EXPRESS;
            $serviceOrder->setCheckoutType($checkout_type);
            // �����ﳵ���ݣ����ɶ���,���ɶ����󣬲���չ��ﳵ�����۳���棬��֧���ɹ�������չ��ﳵ��
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                $genarateStatus = Yii::$service->order->generateOrderByCart($this->_billing, $this->_shipping_method, $this->_payment_method, false,$token);
                if ($genarateStatus) {
                    $innerTransaction->commit();
                } else {
                    $innerTransaction->rollBack();
                }
            } catch (Exception $e) {
                $innerTransaction->rollBack();
            }
            //echo 22;
            if ($genarateStatus) {
                // �õ���ǰ�Ķ�����Ϣ
                $doExpressCheckoutReturn = $this->doExpressCheckoutPayment($token);
                //echo $doExpressCheckoutReturn;exit;
                //echo 333;
                if ($doExpressCheckoutReturn) {
                    $increment_id = Yii::$service->order->getSessionIncrementId();
                    $innerTransaction = Yii::$app->db->beginTransaction();
                    try {
                        // �����������Ƿ�֧�����������֧��������ع�
                        if(!Yii::$service->order->checkOrderVersion($increment_id)){    
                            $innerTransaction->rollBack();
                            return [
                                'code' => 401,
                                'content' => 'the order has been paid',
                            ];
                        }
                        $ExpressOrderPayment = Yii::$service->payment->paypal->updateExpressOrderPayment($doExpressCheckoutReturn,$token);
                        // ���֧���ɹ���������Ϣ���µ��˶��������У����������Ĳ�����
                        //echo 444;
                        if ($ExpressOrderPayment) {
                            // �鿴�����Ƿ񱻶��֧������������֧������ع�
                            
                            // ֧���ɹ�������չ��ﳵ���ݡ������������ɶ�����ʱ��
                            Yii::$service->cart->clearCartProductAndCoupon();
                            // (ɾ��)֧���ɹ��󣬿۳���档
                            // (ɾ��)Yii::$service->product->stock->deduct();
                            // echo 555;
                            // �����¶����ʼ�

                            // �۳������Ż�ȯ
                            // �����ɶ�����ʱ���Ѿ��۳��ˡ��ο�order service GenerateOrderByCart() function

                            // �õ�֧����תǰ��׼��ҳ�档
                            //$paypal_express = Yii::$service->payment->paypal->express_payment_method;
                            //$successRedirectUrl = Yii::$service->payment->getExpressSuccessRedirectUrl($paypal_express);
                            //Yii::$service->url->redirect($successRedirectUrl);
                            $innerTransaction->commit();
                            
                            return [
                                'code' => 200,
                                'content' => 'order generate and pay success'
                            ];
                        }else{
                            
                            $innerTransaction->rollBack();
                            return [
                                'code' => 401,
                                'content' => 'order pay by paypal fail',
                            ];
                        }
                    } catch (Exception $e) {
                        $innerTransaction->rollBack();
                        return false;
                    }
                }
                // �������֧��������ʧ�ܣ�������ȡ����
                /* 2017-09-12�޸ģ���Ϊû�б�Ҫȡ�����������ȡ��������֧��ҳ����޷������µ������ע�͵�����Ĵ���
                if (!$doExpressCheckoutReturn || !$ExpressOrderPayment) {
                    $innerTransaction = Yii::$app->db->beginTransaction();
                    try {
                        if(Yii::$service->order->cancel()){
                            $innerTransaction->commit();
                        }else{
                            $innerTransaction->rollBack();
                        }
                    } catch (Exception $e) {
                        $innerTransaction->rollBack();
                    }
                }
                */
                //return true;
            }else{
                return[
                    'code' => 401,
                    'content' => 'generate order fail'
                ];
            }
            
        }
        //echo 'eeeeeeee';exit;
        Yii::$service->page->message->addByHelperErrors();

        return false;
    }
    /**
     * @property $token | String 
     * ͨ��paypal��api�ӿڣ�����֧���µ�
     */
    public function doExpressCheckoutPayment($token)
    {
        $methodName_ = 'DoExpressCheckoutPayment';
        $nvpStr_ = Yii::$service->payment->paypal->getExpressCheckoutPaymentNvpStr($token);
        //echo $nvpStr_;exit;
        $DoExpressCheckoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
        //var_dump($DoExpressCheckoutReturn);
        //exit;
        if (strstr(strtolower($DoExpressCheckoutReturn['ACK']), 'success')) {
            return $DoExpressCheckoutReturn;
        } else {
            if ($DoExpressCheckoutReturn['ACK'] == 'Failure') {
                $message = $DoExpressCheckoutReturn['L_LONGMESSAGE0'];
                // ��ӱ�����Ϣ��
                //Message::error($message);
                Yii::$service->helper->errors->add($message);
            } else {
                Yii::$service->helper->errors->add('paypal express payment error.');
            }

            return false;
        }
    }

    /**
     * @property $post | Array
     * ��¼�û���������˵�ַ��customer address ��Ȼ������ɵ�
     * address_id д�뵽cart�С�
     * shipping methodд�뵽cart��
     * payment method д�뵽cart�� updateCart
     */
    public function updateAddress($post)
    {
        return Yii::$service->cart->updateGuestCart($this->_billing, $this->_shipping_method, $this->_payment_method);
    }

    /**
     * ������οͣ���ô������˵�ַ�����ﳵ��
     */
    /*
    public function updateCart(){
        if(Yii::$app->user->isGuest){
            return Yii::$service->cart->updateGuestCart($this->_billing,$this->_shipping_method,$this->_payment_method);
        }else{
            return Yii::$service->cart->updateLoginCart($this->_address_id,$this->_shipping_method,$this->_payment_method);
        }
    }
    */

    /**
     * @property $post | Array
     * @return bool
     *              ���ǰ̨���ݵ���Ϣ�Ƿ���ȷ��ͬʱ��ʼ��һ���������
     */
    public function checkOrderInfoAndInit($post)
    {
        $address_one = '';
        $billing = isset($post['billing']) ? $post['billing'] : '';
        if (!Yii::$service->order->checkRequiredAddressAttr($billing)) {
            return [
                'code' => 401,
                'content' => 'address info error',
            ];
        }
        $this->_billing = $billing;

        $shipping_method = isset($post['shipping_method']) ? $post['shipping_method'] : '';
        $payment_method = isset($post['payment_method']) ? $post['payment_method'] : '';
        // ��֤���˷�ʽ
        if (!$shipping_method) {
            return [
                'code' => 401,
                'content' => 'shipping method can not empty',
            ];
        } else {
            if (!Yii::$service->shipping->ifIsCorrect($shipping_method)) {
                
                return [
                    'code' => 401,
                    'content' => 'shipping method is not correct',
                ];
            }
        }

        $this->_shipping_method = $shipping_method;
        $this->_payment_method = $payment_method;
        Yii::$service->payment->setPaymentMethod($this->_payment_method);

        return true;
    }
}
