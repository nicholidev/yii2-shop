<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

use fecshop\services\Service;

use Yii;

/**
 * Product ProductMysqldb Service δ������
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductMysqldb extends Service implements ProductInterface
{
    public $numPerPage = 20;
    
    protected $_productModelName = '\fecshop\models\mysqldb\Product';

    protected $_productModel;
    
    public function init()
    {
        parent::init();
        list($this->_productModelName, $this->_productModel) = \Yii::mapGet($this->_productModelName);
    }
    
    public function getPrimaryKey()
    {
        return 'id';
    }

    /**
     * �õ����༤��״̬��ֵ
     */
    public function getEnableStatus()
    {
        $model = $this->_productModel;
        return $model::STATUS_ENABLE;
    }
    
    public function getByPrimaryKey($primaryKey = null)
    {
        if ($primaryKey) {
            return $this->_productModel->findOne($primaryKey);
        } else {
            return new $this->_productModelName();
        }
    }

    /**
     * @param $sku|array
     * @param $returnArr|bool ���ص������Ƿ��������ʽ���������Ϊ
     *		false���򷵻ص��Ƕ�������
     * @return array or Object
     *               ͨ��sku ��ȡ��Ʒ��һ����Ʒ
     */
    public function getBySku($sku, $returnArr = true)
    {
        if ($sku) {
            if ($returnArr) {
                $product = $this->_productModel->find()->asArray()
                    ->where(['sku' => $sku])
                    ->one();
            } else {
                $product = $this->_productModel->findOne(['sku' => $sku]);
            }
            $primaryKey = $this->getPrimaryKey();
            if (isset($product[$primaryKey]) && !empty($product[$primaryKey])) {
                return $product;
            }
        }
    }

    /**
     * @param $spu|array
     * @param $returnArr|bool ���ص������Ƿ��������ʽ���������Ϊ
     *		false���򷵻ص��Ƕ�������
     * @return array or Object
     *               ͨ��spu ��ȡ��Ʒ����
     */
    public function getBySpu($spu, $returnArr = true)
    {
        if ($spu) {
            if ($returnArr) {
                return $this->_productModel->find()->asArray()
                    ->where(['spu' => $spu])
                    ->all();
            } else {
                return $this->_productModel->find()
                    ->where(['spu' => $spu])
                    ->all();
            }
        }
    }

    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_productModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return [
            'coll' => $query->all(),
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * ��coll()�Ĳ�ͬ���ڣ��÷�ʽ����active record����˿��Ի�ȡ��Ʒ���������ݵġ�
     * �����ַ�ʽ�������ƹ���Ʒ�����飬��Ϊ��Ʒ��������Ҫ���ݲ�ͬ��
     * �����飬��active record ���渽����Ӧ�����ԣ���api���ֲ��ʺϡ�
     */
    public function apicoll()
    {
        $collection = $this->_productModel->find()->getCollection();
        $cursor = $collection->find();
        $count = $collection->count();
        $arr = [];
        foreach ($cursor as $k =>$v) {
            $v['_id'] = (string) $v['_id'];
            $arr[$k] = $v;
        }

        return [
            'coll' => $arr,
            'count'=> $count,
        ];
    }

    /**
     * @param $primaryKey | String ����
     * @return  array ����getByPrimaryKey()�Ĳ�ͬ���ڣ��÷�ʽ����active record����˿��Ի�ȡ��Ʒ���������ݵġ�
     */
    public function apiGetByPrimaryKey($primaryKey)
    {
        $collection = $this->_productModel->find()->getCollection();
        $cursor = $collection->findOne(['_id' => $primaryKey]);
        $arr = [];
        foreach ($cursor as $k => $v) {
            $arr[$k] = $v;
        }

        return $arr;
    }

    /**
     * @param $product_one | String ��Ʒ�������顣���Ҫ��mongodb���汣��Ĳ�Ʒ���ݸ�ʽһ�¡�
     * ͨ��api�����Ʒ
     */
    public function apiSave($product_one)
    {
        $collection = $this->_productModel->find()->getCollection();
        $collection->save($product_one);

        return true;
    }

    /**
     * @param $primaryKey | String
     * ͨ��apiɾ����Ʒ
     */
    public function apiDelete($primaryKey)
    {
        $collection = $this->_productModel->find()->getCollection();
        $collection->remove(['_id' => $primaryKey]);

        return true;
    }

    /*
     * @param $filter | Array �� example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
     *          ['>','price',1],
     *          ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	    'asArray' => true,
     * ]
     * �õ�������
     */
    public function collCount($filter = [])
    {
        $query = $this->_productModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return $query->count();
    }

    /**
     * @param  $product_id_arr | Array
     * @param  $category_id | String
     * �ڸ���Ĳ�Ʒid����$product_id_arr�У��ҳ�����Щ��Ʒ���ڷ��� $category_id
     * �ù����Ǻ�̨����༭�У���Ӧ�ķ����Ʒ�б���
     * Ҳ�����ڵ�ǰ�ķ����£��鿴���еĲ�Ʒ�����ڵ�ǰ����Ĳ�Ʒ��Ĭ�ϱ���ѡ��
     */
    public function getCategoryProductIds($product_id_arr, $category_id)
    {
        $id_arr = [];
        if (is_array($product_id_arr) && !empty($product_id_arr)) {
            $query = $this->_productModel->find()->asArray();
            $mongoIds = [];
            foreach ($product_id_arr as $id) {
                $mongoIds[] = new \MongoDB\BSON\ObjectId($id);
            }
            //var_dump($mongoIds);
            $query->where(['in', $this->getPrimaryKey(), $mongoIds]);
            $query->andWhere(['category'=>$category_id]);
            $data = $query->all();
            if (is_array($data) && !empty($data)) {
                foreach ($data as $one) {
                    $id_arr[] = $one[$this->getPrimaryKey()];
                }
            }
        }
        
        return $id_arr;
    }

    /**
     * @param $attr_group | String
     * ���ݲ�Ʒ�������������õ��������飬Ȼ���������鸽�ӵ�Product(model)�������С�
     */
    public function addGroupAttrs($attr_group)
    {
        $attrInfo = Yii::$service->product->getGroupAttrInfo($attr_group);
        if (is_array($attrInfo) && !empty($attrInfo)) {
            $attrs = array_keys($attrInfo);
            $this->_productModel->addCustomProductAttrs($attrs);
        }
    }

    /**
     * @param $one|array , ��Ʒ��������
     * @param $originUrlKey|string , ��Ʒ��ԭ����url key ��Ҳ������ǰ�ˣ�������Զ���url��
     * �����Ʒ������͸��£����Լ������Ʒ���Զ���url
     * ����ύ�������ж������Զ���url�������Զ���url���浽urlkey�У����û���Զ���urlkey�����ʹ��name�������ɡ�
     */
    public function save($one, $originUrlKey = 'catalog/product/index')
    {
        if (!$this->initSave($one)) {
            return false;
        }
        $one['min_sales_qty'] = (int)$one['min_sales_qty'];
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if ($primaryVal) {
            $model = $this->_productModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('Product {primaryKey} is not exist', ['primaryKey'=>$this->getPrimaryKey()]);

                return false;
            }

            //��֤sku �Ƿ��ظ�
            $product_one = $this->_productModel->find()->asArray()->where([
                '<>', $this->getPrimaryKey(), (new \MongoDB\BSON\ObjectId($primaryVal)),
            ])->andWhere([
                'sku' => $one['sku'],
            ])->one();
            if ($product_one['sku']) {
                Yii::$service->helper->errors->add('Product Sku is exist��please use other sku');

                return false;
            }
        } else {
            $model = new $this->_productModelName();
            $model->created_at = time();
            $model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
            $primaryVal = new \MongoDB\BSON\ObjectId();
            $model->{$this->getPrimaryKey()} = $primaryVal;
            //��֤sku �Ƿ��ظ�
            $product_one = $this->_productModel->find()->asArray()->where([
                'sku' => $one['sku'],
            ])->one();
            if ($product_one['sku']) {
                Yii::$service->helper->errors->add('Product Sku is exist��please use other sku');

                return false;
            }
        }
        $model->updated_at = time();
        // ���������Ʒ�����ռ۸�
        $one['final_price'] = Yii::$service->product->price->getFinalPrice($one['price'], $one['special_price'], $one['special_from'], $one['special_to']);
        $one['score'] = (int) $one['score'];
        unset($one['_id']);
        /**
         * �����Ʒ
         */
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        /**
         * ��� $one['custom_option'] ��Ϊ�գ��������������������д��qty
         */
        if (is_array($one['custom_option']) && !empty($one['custom_option'])) {
            $custom_option_qty = 0;
            foreach ($one['custom_option'] as $co_one) {
                $custom_option_qty += $co_one['qty'];
            }
            $model->qty = $custom_option_qty;
        }
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        // �Զ���url����
        if ($originUrlKey) {
            $originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
            $originUrlKey = isset($one['url_key']) ? $one['url_key'] : '';
            $defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'], 'name');
            $urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
            $model->url_key = $urlKey;
            $model->save();
        }
        $product_id = $model->{$this->getPrimaryKey()};
        /**
         * ���²�Ʒ��档
         */
        Yii::$service->product->stock->saveProductStock($product_id, $one);
        /**
         * ���²�Ʒ��Ϣ��������
         */
        Yii::$service->search->syncProductInfo([$product_id]);

        return $model;
    }

    /**
     * @param $one|array
     * �Ա�������ݽ���������֤
     * sku  spu   Ĭ������name �� Ĭ������description����Ϊ�ա�
     */
    protected function initSave(&$one)
    {
        $primaryKey = $this->getPrimaryKey();
        $PrimaryVal = 1;
        if (!isset($one[$primaryKey]) || !$one[$primaryKey]) {
            $PrimaryVal = 0;
        }
        if (!$PrimaryVal && (!isset($one['sku']) || empty($one['sku']))) {
            Yii::$service->helper->errors->add('sku must exist');

            return false;
        }
        if (!$PrimaryVal && (!isset($one['spu']) || empty($one['spu']))) {
            Yii::$service->helper->errors->add('spu must exist');

            return false;
        }
        $defaultLangName = \Yii::$service->fecshoplang->getDefaultLangAttrName('name');
        if ($PrimaryVal && $one['name'] && empty($one['name'][$defaultLangName])) {
            Yii::$service->helper->errors->add('name {default_lang_name} can not empty', ['default_lang_name' => $defaultLangName]);

            return false;
        }
        if (!isset($one['name'][$defaultLangName]) || empty($one['name'][$defaultLangName])) {
            Yii::$service->helper->errors->add('name {default_lang_name} can not empty', ['default_lang_name' => $defaultLangName]);

            return false;
        }
        $defaultLangDes = \Yii::$service->fecshoplang->getDefaultLangAttrName('description');
        if ($PrimaryVal && $one['description'] && empty($one['description'][$defaultLangDes])) {
            Yii::$service->helper->errors->add('description {default_lang_des} can not empty', ['default_lang_des' => $defaultLangDes]);

            return false;
        }
        if (!isset($one['description'][$defaultLangDes]) || empty($one['description'][$defaultLangDes])) {
            Yii::$service->helper->errors->add('description {default_lang_des} can not empty', ['default_lang_des' => $defaultLangDes]);

            return false;
        }
        if (is_array($one['custom_option']) && !empty($one['custom_option'])) {
            $new_custom_option = [];
            foreach ($one['custom_option'] as $k=>$v) {
                $k = preg_replace('/[^A-Za-z0-9\-_]/', '', $k);
                $new_custom_option[$k] = $v;
            }
            $one['custom_option'] = $new_custom_option;
        }

        return true;
    }

    /**
     * @param $ids | Array or String
     * ɾ����Ʒ�����ids�����飬��ɾ�������Ʒ��������ַ�������ɾ��һ����Ʒ
     * �ڲ�Ʒ��Ʒ��ͬʱ������url rewrite����ɾ����Ӧ���Զ���url���ݡ�
     */
    public function remove($ids)
    {
        if (empty($ids)) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids)) {
            $removeAll = 1;
            foreach ($ids as $id) {
                $model = $this->_productModel->findOne($id);
                if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                    $url_key = $model['url_key'];
                    // ɾ������дurl��������ݡ�
                    Yii::$service->url->removeRewriteUrlKey($url_key);
                    // ɾ�����������������ԣ����������
                    Yii::$service->search->removeByProductId($id);
                    Yii::$service->product->stock->removeProductStock($id);
                    $model->delete();
                //$this->removeChildCate($id);
                } else {
                    Yii::$service->helper->errors->add('Product Remove Errors:ID:{id} is not exist', ['id'=>$id]);
                    $removeAll = 0;
                }
            }
            if (!$removeAll) {
                return false;
            }
        } else {
            $id = $ids;
            $model = $this->_productModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $url_key = $model['url_key'];
                // ɾ������дurl��������ݡ�
                Yii::$service->url->removeRewriteUrlKey($url_key);
                // ɾ�����������������
                Yii::$service->search->removeByProductId($model[$this->getPrimaryKey()]);
                Yii::$service->product->stock->removeProductStock($id);
                $model->delete();
            //$this->removeChildCate($id);
            } else {
                Yii::$service->helper->errors->add('Product Remove Errors:ID:{id} is not exist.', ['id'=>$id]);

                return false;
            }
        }

        return true;
    }

    /**
     * @param $category_id | String  �����id��ֵ
     * @param $addCateProductIdArr | Array ��������Ҫ��ӵĲ�Ʒid���飬Ҳ���Ǹ�������������⼸����Ʒ��
     * @param $deleteCateProductIdArr | Array ��������Ҫɾ���Ĳ�Ʒid���飬Ҳ�����������������ȥ���⼸����Ʒ�Ķ�Ӧ��ϵ��
     * ��������Ǻ�̨����༭������ʹ�õ��ĺ������ڷ����п���һ������Ӷ����Ʒ��Ҳ����ɾ�������Ʒ����Ʒ�ͷ����Ƕ�Զ�Ĺ�ϵ��
     */
    public function addAndDeleteProductCategory($category_id, $addCateProductIdArr, $deleteCateProductIdArr)
    {
        // �� addCategoryIdArr �鿴��Щ��Ʒ������id��product���Ѿ����ڣ�
        $idKey = $this->getPrimaryKey();
        //var_dump($addCateProductIdArr);
        if (is_array($addCateProductIdArr) && !empty($addCateProductIdArr) && $category_id) {
            $addCateProductIdArr = array_unique($addCateProductIdArr);
            foreach ($addCateProductIdArr as $product_id) {
                if (!$product_id) {
                    continue;
                }
                $product = $this->_productModel->findOne($product_id);
                if (!$product[$idKey]) {
                    continue;
                }
                $category = $product->category;
                $category = ($category && is_array($category)) ? $category : [];
                //echo $category_id;
                if (!in_array($category_id, $category)) {
                    //echo $category_id;
                    $category[] = $category_id;
                    $product->category = $category;
                    $product->save();
                }
            }
        }

        if (is_array($deleteCateProductIdArr) && !empty($deleteCateProductIdArr) && $category_id) {
            $deleteCateProductIdArr = array_unique($deleteCateProductIdArr);
            foreach ($deleteCateProductIdArr as $product_id) {
                if (!$product_id) {
                    continue;
                }
                $product = $this->_productModel->findOne($product_id);
                if (!$product[$idKey]) {
                    continue;
                }
                $category = $product->category;
                if (in_array($category_id, $category)) {
                    $arr = [];
                    foreach ($category as $c) {
                        if ($category_id != $c) {
                            $arr[] = $c;
                        }
                    }
                    $product->category = $arr;
                    $product->save();
                }
            }
        }
    }

    /**
     * ͨ��where���� �� ���ҵ�select �ֶ���Ϣ���õ���Ʒ���б���Ϣ��
     * ����һ��������ǰ̨�������ԵĲ���ҳ�Ĳ�Ʒ���ҡ�
     * �������û�н��н�һ��������Ҫǰ�˻�ȡ���ݺ��ڴ���
     */
    public function getProducts($filter)
    {
        $where = $filter['where'];
        if (empty($where)) {
            return [];
        }
        $select = $filter['select'];
        $query = $this->_productModel->find()->asArray();
        $query->where($where);
        $query->andWhere(['status' => $this->getEnableStatus()]);
        if (is_array($select) && !empty($select)) {
            $query->select($select);
        }

        return $query->all();
    }
    /**
     * �õ�����ҳ��Ĳ�Ʒ�б�
     * $filter ��������ϸ���ο����� getFrontCategoryProductsGroupBySpu($filter);
     */
    public function getFrontCategoryProducts($filter){
        if (Yii::$service->product->productSpuShowOnlyOneSku) {
            
            return $this->getFrontCategoryProductsGroupBySpu($filter);
        } else {
            
            return $this->getFrontCategoryProductsAll($filter);
        }
    }
    /**
     * �õ�����ҳ��Ĳ�Ʒ��All��
     * $filter ��������ϸ���ο����� getFrontCategoryProductsGroupBySpu($filter);
     */
    public function getFrontCategoryProductsAll($filter){
        $where = $filter['where'];
        if (empty($where)) {
            return [];
        }
        if (!isset($where['status'])) {
            $where['status'] = $this->getEnableStatus();
        }
        $orderBy = $filter['orderBy'];
        $pageNum = $filter['pageNum'];
        $numPerPage = $filter['numPerPage'];
        $select = $filter['select'];
        $where_c = [];
        foreach ($where as $k => $v) {
            $where_c[] = [$k => $v];
        }
        $filter = [
            'numPerPage' 	=> $numPerPage,
     		'pageNum'		    => $pageNum,
      		'orderBy'	        => $orderBy,
      		'where'			    => $where_c,
      	    'asArray'           => true,
        ];
        
        return $this->coll($filter);
    }
    
    
    /**
     * ��ͬspu���������sku��ֻ��ʾһ����ȡscoreֵ��ߵ��Ǹ���ʾ
     *[
     *	'category_id' 	=> 1,
     *	'pageNum'		=> 2,
     *	'numPerPage'	=> 50,
     *	'orderBy'		=> 'name',
     *	'where'			=> [
     *		['>','price',11],
     *		['<','price',22],
     *	],
     *	'select'		=> ['xx','yy'],
     *	'group'			=> '$spu',
     * ]
     * �õ������µĲ�Ʒ����������Ҫע����ǣ�
     * 1.ͬһ��spu�Ĳ�Ʒ���кܶ�sku������ֻ��ʾscore��ߵĲ�Ʒ�����score����ͨ���ű�ȡ���������������һ���£�����
     *   ��������µȵȣ��������Զ��嶼���ԡ�
     * 2.�������filter�����orderBy����
     * 3.����ʹ�õ���mongodb��aggregate(�ܵ�)��������ˣ��˺�����һ�������ƣ����Ǹú���
     *   �����Ľ�����ܴ�Լ32MB����ˣ����һ����������Ĳ�Ʒ��ʮ���ʱ����ܾͻ�������⣬
     *   �������������רҵ�������������ۺϹ��ߡ�
     *   ����������һ����û���˵����������Ϊƿ�����⣬һ��һ�������µĲ�Ʒ������ּ�ʮ��������
     * 4.���͵õ�spuΨһ�Ĳ�Ʒ�б����spu��ͬ��sku��ͬ�Ĳ�Ʒ��ֻҪscore��ߵ��Ǹ���.
     */
    public function getFrontCategoryProductsGroupBySpu($filter)
    {
        $where = $filter['where'];
        if (empty($where)) {
            return [];
        }
        if (!isset($where['status'])) {
            $where['status'] = $this->getEnableStatus();
        }
        $orderBy = $filter['orderBy'];
        $pageNum = $filter['pageNum'];
        $numPerPage = $filter['numPerPage'];
        $select = $filter['select'];
        $group['_id'] = $filter['group'];
        $project = [];
        foreach ($select as $column) {
            $project[$column] = 1;
            $group[$column] = ['$first' => '$'.$column];
        }
        $group['product_id'] = ['$first' => '$product_id'];
        $langCode = Yii::$service->store->currentLangCode;
        
        $name_lang  = Yii::$service->fecshoplang->getLangAttrName('name', $langCode);
        $default_name_lang  = Yii::$service->fecshoplang->GetDefaultLangAttrName('name');
        $project['name'] = [
            $default_name_lang => 1,
            $name_lang => 1,
        ];
        $project['product_id'] = '$_id';
        $pipelines = [
            [
                '$match'    => $where,
            ],
            [
                '$sort' => [
                    'score' => -1,
                ],
            ],
            [
                '$project'    => $project,
            ],
            [
                '$group'    => $group,
            ],
            [
                '$sort'    => $orderBy,
            ],
            [
                '$limit'    => Yii::$service->product->categoryAggregateMaxCount,
            ],
        ];
        // ['cursor' => ['batchSize' => 2]]
        $product_data = $this->_productModel->getCollection()->aggregate($pipelines);
        $product_total_count = count($product_data);
        $pageOffset = ($pageNum - 1) * $numPerPage;
        $products = array_slice($product_data, $pageOffset, $numPerPage);

        return [
            'coll' => $products,
            'count' => $product_total_count,
        ];
    }

    /**
     * @param $filter_attr | String ��Ҫ����ͳ�Ƶ��ֶ�����
     * @propertuy $where | Array  ���������������ҪЩmongodb������������
     * �õ����Ǹ����ԣ��Լ���Ӧ�ĸ�����
     * �������������ǰ�˷�������������Թ��ˡ�
     */
    public function getFrontCategoryFilter($filter_attr, $where)
    {
        if (empty($where)) {
            return [];
        }
        if (!isset($where['status'])) {
            $where['status'] = $this->getEnableStatus();
        }
        $group['_id'] = '$'.$filter_attr;
        $group['count'] = ['$sum'=> 1];
        $project = [$filter_attr => 1];
        $pipelines = [
            [
                '$match'    => $where,
            ],
            [
                '$project'    => $project,
            ],
            [
                '$group'    => $group,
            ],
            [
                '$limit'    => Yii::$service->product->categoryAggregateMaxCount,
            ],
        ];
        $filter_data = $this->_productModel->getCollection()->aggregate($pipelines);

        return $filter_data;
    }

    /**
     * @param $spu | String
     * @param $avag_rate | Int ��ƽ������
     * @param $count | Int �����۴���
     * @param $lang_code | String �����Լ���
     * @param $avag_lang_rate | Int ��������ƽ������
     * @param $lang_count | Int �� ���������۴�����
     * @param $rate_total_arr | Array, �������Ƕ�Ӧ�ĸ���
     * @param $rate_lang_total_arr | Array, �������¸������Ƕ�Ӧ�ĸ���
     */
    public function updateProductReviewInfo($spu, $avag_rate, $count, $lang_code, $avag_lang_rate, $lang_count, $rate_total_arr, $rate_lang_total_arr)
    {
        $data = $this->_productModel->find()->where([
            'spu' => $spu,
        ])->all();
        if (!empty($data) && is_array($data)) {
            $attrName = 'reviw_rate_star_average_lang';
            $review_star_lang = Yii::$service->fecshoplang->getLangAttrName($attrName, $lang_code);
            $attrName = 'review_count_lang';
            $review_count_lang = Yii::$service->fecshoplang->getLangAttrName($attrName, $lang_code);
            $reviw_rate_star_info_lang = Yii::$service->fecshoplang->getLangAttrName('reviw_rate_star_info_lang', $lang_code);
            foreach ($data as $one) {
                $one['reviw_rate_star_average'] = $avag_rate;
                $one['review_count']            = $count;
                $a                              = $one['reviw_rate_star_average_lang'];
                $a[$review_star_lang]           = $avag_lang_rate;
                $b                              = $one['review_count_lang'];
                $b[$review_count_lang]          = $lang_count;
                $one['reviw_rate_star_average_lang'] = $a;
                $one['review_count_lang']           = $b;
                $one['reviw_rate_star_info']        = $rate_total_arr;
                $c                                  = $one['reviw_rate_star_info_lang'];
                $c[$reviw_rate_star_info_lang]      = $rate_lang_total_arr;
                $one['reviw_rate_star_info_lang']   = $c;
                $one->save();
            }
        }
    }

    public function updateAllScoreToZero(){
        return $this->_productModel->getCollection()->update([], ['score' => 0]);
    }
}
