<?php

namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiController;
use Yii;

class PcateController extends AppapiController
{
    public $modelClass;

    public function init()
    {
        // �õ���ǰservice��Ӧ��model
        $this->modelClass = Yii::$service->category->getModelName();
        parent::init();
    }
}
