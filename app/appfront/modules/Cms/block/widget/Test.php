<?php
namespace fecshop\app\appfront\modules\Cms\block\widget;
use Yii;
use fecshop\app\appfront\modules\AppfrontController;
class Test 
{
	public $terry;
	# ��վ��Ϣ����
    public function getLastData()
    {
		return [
			'i'   	=> $this->terry,
			'love' 	=> 'loves',
			'you' 	=> 'terry',
		];
	}
}



