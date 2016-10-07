<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\helper;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\Service;
/**
 * Helper Captcha services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Captcha extends Service
{
	public $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ0123456789';//�������
	public $codelen 	= 4;//��֤�볤��
	public $width 		= 130;//���
	public $height 		= 50;//�߶�
	public $fontsize 	= 20;//ָ�������С
	public $case_sensitive = false;
	private $fontcolor		;//ָ��������ɫ
	private $code;//��֤��
	private $img;//ͼ����Դ���
	private $font;//ָ��������
	private $_sessionKey = 'captcha_session_key';
	
	/**
	 *  1. ����ͼƬ��
		public function actionCaptcha(){
			Yii::$service->helper->captcha->doimg();
			exit;
		}
		2. ����ͼƬ��
			<p>
				<span>��֤�룺</span>
				<input type="text" name="validate" value="" size=10> 
				<img  title="���ˢ��" src="http://fecshop.appfront.fancyecommerce.com/site/helper/captcha" align="absbottom" onclick="this.src='captcha.php?'+Math.random();"></img>
			</p>
		3. ��֤����֤�ɹ�����true ʧ�ܷ���false
		$code = ''  // code���û����ݹ�����ֵ��
		Yii::$service->helper->captcha->validateCaptcha($code)
	    
	 *
	 */
	//���췽����ʼ��
	public function __construct() {
		$this->font = dirname(__FILE__).'/captcha/Elephant.ttf';//ע������·��Ҫд�ԣ�������ʾ����ͼƬ
		//echo $this->font;exit;
	}
	//���������
	private function createCode() {
		$_len = strlen($this->charset)-1;
		for ($i=0;$i<$this->codelen;$i++) {
			$this->code .= $this->charset[mt_rand(0,$_len)];
		}
	}
	//���ɱ���
	private function createBg() {
		$this->img = imagecreatetruecolor($this->width, $this->height);
		$color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
		imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
	}
	//��������
	private function createFont() {
		$_x = $this->width / $this->codelen;
		
		for ($i=0;$i<$this->codelen;$i++) {
			if(!$this->fontcolor){
				$fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			}else{
				$fontcolor = $this->fontcolor;
			}
			imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$fontcolor,$this->font,$this->code[$i]);
		}
	}
	//����������ѩ��
	private function createLine() {
		//����
		for ($i=0;$i<6;$i++) {
			$color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
		}
		//ѩ��
		for ($i=0;$i<100;$i++) {
			$color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
			imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
		}
	}
	//���
	private function outPut() {
		header('Content-type:image/jpg');
		imagepng($this->img);
		imagedestroy($this->img);
	}
	//��������
	public function doimg() {
		$this->createBg();
		$this->createCode();
		$this->createLine();
		$this->createFont();
		$this->outPut();
		$this->setSessionCode();
	}
	
	public function setSessionCode(){
		$code = $this->getCode($this->code);
		\Yii::$app->session->set($this->_sessionKey,$code);
	}
	//��ȡ��֤��
	public function getCode($code) {
		if(!$this->case_sensitive){
			return strtolower($code);
		}else{
			return $this->code;
		}
	}
	
	public function validateCaptcha($captchaData){
		$captchaData = $this->getCode($captchaData);
		$sessionCaptchaData = \Yii::$app->session->get($this->_sessionKey);
		return ($captchaData === $sessionCaptchaData) ? true : false ;
	}

}