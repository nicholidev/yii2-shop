<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\payment;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mysqldb\IpnMessage;
use fecshop\services\Service;
/**
 * Payment Paypal services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Paypal extends Service
{
	# paypal֧��״̬
	public $payment_status_none         	= 'none';
    public $payment_status_completed    	= 'completed';
    public $payment_status_denied       	= 'denied';
    public $payment_status_expired      	= 'expired';
    public $payment_status_failed      		= 'failed';
    public $payment_status_in_progress  	= 'in_progress';
    public $payment_status_pending  		= 'pending';
    public $payment_status_refunded     	= 'refunded';
    public $payment_status_refunded_part 	= 'partially_refunded';
    public $payment_status_reversed   		= 'reversed';
    public $payment_status_unreversed   	= 'canceled_reversal';
    public $payment_status_processed  		= 'processed';
    public $payment_status_voided     		= 'voided';
	
	public $use_local_certs = true;
	
	protected $_postData;
	protected $_order;
	
	public function receiveIpn(){
		
		
		if($this->verifySecurity()){
			# ��֤�����Ƿ��Ѿ�����
			if($this->isNotDuplicate()){
				# ��֤�����Ƿ񱻴۸ġ�
				
				
		
		
		
		
				if($this->isNotDistort()){
					
					$this->updateOrderAndCoupon();
				}else{
					# ������ݺͶ������ݲ�һ�£����ң�֧��״̬Ϊ�ɹ�����˶���
					# ���Ϊ���ɵġ�
					$suspected_fraud = Yii::$service->order->payment_status_suspected_fraud;
					$this->updateOrderAndCoupon($suspected_fraud);
				}
			}
		}
	}
	
	protected function verifySecurity(){
		$this->_postData = Yii::$app->request->post();
		Yii::$service->payment->setPaymentMethod('paypal_standard');
		$verifyUrl = $this->getVerifyUrl();
		$verifyReturn = $this->curlGet($verifyUrl);
		if($verifyReturn == 'VERIFIED'){
			return true;
		}
	}
	
	protected function getVerifyUrl(){
		$urlParamStr = '';
		if($this->_postData){
			foreach ($this->_postData as $k => $v) {
				$urlParamStr .= '&'.$k.'='.urlencode($v);
			}
		}
		$urlParamStr .= "&cmd=_notify-validate";
		$urlParamStr = substr($urlParamStr, 1);
		$verifyUrl = Yii::$service->payment->getStandardPaymentUrl();
		$verifyUrl = $verifyUrl."?".$urlParamStr;
		return $verifyUrl;
	}
	
	protected function curlGet($url){
		$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        // This is often required if the server is missing a global cert bundle, or is using an outdated one.
        if ($this->use_local_certs) {
            curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . "/cert/paypal.crt");
        }
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        $httpResponse = curl_exec($ch);
		return $httpResponse;
	}
	
	
	# �ж��Ƿ��ظ���������ظ����ѵ�ǰ�Ĳ��롣
	protected function isNotDuplicate(){
		$ipn = IpnMessage::find()
				->asArray()
				->where([
				'txn_id'=>$this->_postData['txn_id'],
				'payment_status'=>$this->_postData['payment_status'],
				])
				->one();
		if(is_array($ipn) && !empty($ipn)){
			return false;
		}else{
			$IpnMessage = new IpnMessage;
			$IpnMessage->txn_id = $this->_postData['txn_id'];
			$IpnMessage->payment_status = $this->_postData['payment_status'];
			$IpnMessage->updated_at = time();
			$IpnMessage->save();
			return true;
		}
	}
	
	
	/**
	 * ��֤���������Ƿ񱻴۸ġ�
	 * ͨ���������ҵ��������鿴�Ƿ����
	 * ��֤�ʼ���ַ����������Ƿ�׼ȷ��
	 */
	protected function isNotDistort(){
		//Yii::$app->mylog->log("begin isNotDistort..");
		$increment_id 		= $this->_postData['invoice'];
		$mc_gross 			= $this->_postData['mc_gross'];
		$mc_currency 		= $this->_postData['mc_currency'];
		
		if($increment_id && $mc_gross && $mc_currency){
			$this->_order = Yii::$service->order->getByIncrementId($increment_id);;
			if($this->_order){
				$order_currency_code = $this->_order['order_currency_code'];
				if($order_currency_code == $mc_currency){
					# �˶Զ����ܶ�
					$currentCurrencyGrandTotal = $this->_order['grand_total'];
					if((float)$currentCurrencyGrandTotal == (float)$mc_gross ){
						return true;
					}else{
						
					}
				}else{
				
				}
			}
		}
		return false;
	}
	/**
	 * @property $orderstatus | String �˿�״̬
	 * ���¶���״̬��
	 */
	public function updateOrderAndCoupon($orderstatus = ''){
		
		
		
		if($this->_postData['txn_type']){
			$this->_order->txn_type = $this->_postData['txn_type'];	
		}	
		if($this->_postData['txn_id']){
			$this->_order->txn_id = $this->_postData['txn_id'];
		}
		if($this->_postData['payer_id']){
			$this->_order->payer_id = $this->_postData['payer_id'];
		}
		if($this->_postData['ipn_track_id']){
			$this->_order->ipn_track_id = $this->_postData['ipn_track_id'];
		}
		if($this->_postData['receiver_id']){
			$this->_order->receiver_id = $this->_postData['receiver_id'];
		}
		if($this->_postData['verify_sign']){
			$this->_order->verify_sign = $this->_postData['verify_sign'];
		}
		if($this->_postData['charset']){
			$this->_order->charset = $this->_postData['charset'];
		}
		if($this->_postData['mc_fee']){
			$this->_order->payment_fee = $this->_postData['mc_fee'];
			$currency = $this->_postData['mc_currency'];
			$this->_order->base_payment_fee = Yii::$service->page->currency->getBaseCurrencyPrice($this->_postData['mc_fee'],$currency);
		}
		if($this->_postData['payment_type']){
			$this->_order->payment_type = $this->_postData['payment_type'];
		}
		if($this->_postData['payment_date']){
			$this->_order->paypal_order_datetime = date("Y-m-d H:i:s",$this->_postData['payment_date']);
		}
		if($this->_postData['protection_eligibility']){
			$this->_order->protection_eligibility = $this->_postData['protection_eligibility'];
		}
		$this->_order->updated_at = time();
		$innerTransaction = Yii::$app->db->beginTransaction();
		try {
			if($orderstatus){
				# ָ���˶���״̬
				$this->_order->order_status = $orderstatus;
				$this->_order->save();
				$payment_status = strtolower($this->_postData['payment_status']);
				//Yii::$app->mylog->log('save_'.$orderstatus);
			}else{
				$payment_status = strtolower($this->_postData['payment_status']);
				if($payment_status == $this->payment_status_completed) {
					$this->_order->order_status = Yii::$service->order->payment_status_processing;
					# ���¶�����Ϣ
					$this->_order->save();
					# ���¿��
					//$orderitem = Salesorderitem::find()->asArray()->where(['order_id'=>$this->_order->order_id])->all();
					//Order::updateProductStockQty($orderitem);
					# ����couponʹ����
					//$customer_id = $this->_order['customer_id'];
					//$coupon_code = $this->_order['coupon_code'];
					//if($customer_id && $coupon_code){
					//	Coupon::CouponTakeEffect($customer_id,$coupon_code);
					//}
					#LOG
					//Yii::$app->mylog->log('save_'.Order::ORDER_PROCESSING);
			}else if($payment_status == $this->payment_status_failed){
					$this->_order->order_status = Yii::$service->order->payment_status_canceled;
					$this->_order->save();
				}else if($payment_status == $this->payment_status_refunded){		
					$this->_order->order_status = Yii::$service->order->payment_status_canceled;
					$this->_order->save();
				}else{
					
				}
			}	
			$innerTransaction->commit();
			return true;
		} catch (Exception $e) {
			$innerTransaction->rollBack();
		}
		return false;
	}
	
	
	
}