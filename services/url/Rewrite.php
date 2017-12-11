<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\url;

use fecshop\services\Service;
use fecshop\services\url\rewrite\RewriteMongodb;
use fecshop\services\url\rewrite\RewriteMysqldb;

/**
 * Url Rewrite services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Rewrite extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage     = 'RewriteMongodb';   // 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改
    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = ''; 
    protected $_urlRewrite;

    public function init()
    {
        parent::init();
        $currentService = $this->getStorageService($this);
        $this->_urlRewrite = new $currentService();
        /*
        if ($this->storage == 'mongodb') {
            $this->_urlRewrite = new RewriteMongodb();
        } elseif ($this->storage == 'mysqldb') {
            $this->_urlRewrite = new RewriteMysqldb();
        }
        */
    }
    /**
     * @property $urlKey | string 
     * 通过重写后的urlkey字符串，去url_rewrite表中查询，找到重写前的url字符串。
     */
    protected function actionGetOriginUrl($urlKey)
    {
        return $this->_urlRewrite->getOriginUrl($urlKey);
    }

    /**
     * get artile's primary key.
     */
    protected function actionGetPrimaryKey()
    {
        return $this->_urlRewrite->getPrimaryKey();
    }

    /**
     * get artile model by primary key.
     */
    protected function actionGetByPrimaryKey($primaryKey)
    {
        return $this->_urlRewrite->getByPrimaryKey($primaryKey);
    }

    //public function getById($id){
    //	return $this->_article->getById($id);
    //}

    /**
     * @property $filter|array
     * get artile collection by $filter
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		where'			=> [
     ['>','price',1],
     ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    protected function actionColl($filter = '')
    {
        return $this->_urlRewrite->coll($filter);
    }

    /**
     * @property $one|array , save one data .
     * @property $originUrlKey|string , article origin url key.
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    protected function actionSave($one)
    {
        return $this->_urlRewrite->save($one);
    }
    /**
     * @property $ids | Array or String or Int 
     * 删除相应的url rewrite 记录
     */
    protected function actionRemove($ids)
    {
        return $this->_urlRewrite->remove($ids);
    }
    /**
     * @property $time | Int
     * 根据updated_at 更新时间，删除相应的url rewrite 记录
     */
    protected function actionRemoveByUpdatedAt($time)
    {
        return $this->_urlRewrite->removeByUpdatedAt($time);
    }
    /**
     * 返回url rewrite model 对应的query
     */
    protected function actionFind()
    {
        return $this->_urlRewrite->find();
    }
    /**
     * 返回url rewrite 查询结果
     */
    protected function actionFindOne($where)
    {
        return $this->_urlRewrite->findOne($where);
    }
    /**
     * 返回url rewrite model
     */
    protected function actionNewModel()
    {
        return $this->_urlRewrite->newModel();
    }
}
