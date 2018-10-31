<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\admin;

//use fecshop\models\mysqldb\cms\StaticBlock;
use Yii;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class RoleUrlKey extends Service
{
    public $numPerPage = 20;

    protected $_modelName = '\fecshop\models\mysqldb\admin\RoleUrlKey';

    protected $_model;

    /**
     *  language attribute.
     */
    protected $_lang_attr = [
    ];

    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = Yii::mapGet($this->_modelName);
    }

    public function getPrimaryKey()
    {
        return 'id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_model->findOne($primaryKey);
            foreach ($this->_lang_attr as $attrName) {
                if (isset($one[$attrName])) {
                    $one[$attrName] = unserialize($one[$attrName]);
                }
            }

            return $one;
        } else {
            return new $this->_modelName();
        }
    }
    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
            'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_model->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        if (!empty($coll)) {
            foreach ($coll as $k => $one) {
                foreach ($this->_lang_attr as $attr) {
                    $one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
                }
                $coll[$k] = $one;
            }
        }
        //var_dump($one);
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @property $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if (!($this->validateUrlKeyRoleId($one))) {
            Yii::$service->helper->errors->add('url key 存在，您必须定义一个唯一的identify ');

            return;
        }
        if ($primaryVal) {
            $model = $this->_model->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('static block '.$this->getPrimaryKey().' is not exist');

                return;
            }
        } else {
            $model = new $this->_modelName();
            $model->created_at = time();
        }
        $model->updated_at = time();
        foreach ($this->_lang_attr as $attrName) {
            if (is_array($one[$attrName]) && !empty($one[$attrName])) {
                $one[$attrName] = serialize($one[$attrName]);
            }
        }
        $primaryKey = $this->getPrimaryKey();
        $model      = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model[$primaryKey];

        return true;
    }

    /**
     * @param int $roleId
     * @param array $url_key_ids
     * 先删除该role_id 对应的所有的数据，然后将这些数据依次插入到表中
     */
    public function repeatSaveRoleUrlKey($roleId, $url_key_ids){
        if ($roleId && is_array($url_key_ids) && !empty($url_key_ids)) {
            $this->_model->deleteAll([
                'role_id' => $roleId
            ]);
            foreach ($url_key_ids as $url_key_id) {
                $model = new $this->_modelName();
                $model->created_at = time();
                $model->updated_at = time();
                $model->url_key_id = $url_key_id;
                $model->role_id = $roleId;
                $model->save();
            }
            return true;
        }
        return false;
    }

    protected function validateUrlKeyRoleId($one)
    {
        $url_key_id = $one['url_key_id'];
        $role_id = $one['role_id'];
        $id = $this->getPrimaryKey();
        $primaryVal = isset($one[$id]) ? $one[$id] : '';
        $where = [
            'url_key_id' => $url_key_id,
            'role_id' => $role_id,
        ];
        $query = $this->_model->find()->asArray();
        $query->where($where);
        if ($primaryVal) {
            $query->andWhere(['<>', $id, $primaryVal]);
        }
        $one = $query->one();
        if (!empty($one)) {
            return false;
        }

        return true;
    }

    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_model->findOne($id);
                $model->delete();
            }
        } else {
            $id = $ids;
            foreach ($ids as $id) {
                $model = $this->_model->findOne($id);
                $model->delete();
            }
        }

        return true;
    }
}
