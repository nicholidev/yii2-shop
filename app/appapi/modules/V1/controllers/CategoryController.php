<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiTokenController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategoryController extends AppapiTokenController
{
    public $numPerPage = 5;
    
    /**
     * Get Lsit Api���õ�Category �б��api
     */
    public function actionList(){
        
        $page = Yii::$app->request->get('page');
        $page = $page ? $page : 1;
        $filter = [
            'numPerPage'    => $this->numPerPage,
            'pageNum'       => $page,
            'asArray'       => true,
        ];
        $data  = Yii::$service->category->coll($filter);
        $coll  = $data['coll'];
        foreach ($coll as $k => $one) {
            // ����mongodb����
            if (isset($one['_id'])) {
                $coll[$k]['id'] = (string)$one['_id'];
                unset($coll[$k]['_id']);
            }
        }
        $count = $data['count'];
        $pageCount = ceil($count / $this->numPerPage);
        $serializer = new \yii\rest\Serializer();
        Yii::$app->response->getHeaders()
            ->set($serializer->totalCountHeader, $count)
            ->set($serializer->pageCountHeader, $pageCount)
            ->set($serializer->currentPageHeader, $page)
            ->set($serializer->perPageHeader, $this->numPerPage);
        if ($page <= $pageCount ) {
            return [
                'code'    => 200,
                'message' => 'fetch category success',
                'data'    => $coll,
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'fetch category fail , exceeded the maximum number of pages',
                'data'    => [],
            ];
        }
    }
    /**
     * Get One Api������url_key �� id �õ�Category �б��api
     */
    public function actionFetchone(){
        $url_key       = Yii::$app->request->get('url_key');
        $primaryKeyVal = Yii::$app->request->get('id');
        $data          = [];
        if ( !$url_key && !$primaryKeyVal ) {
            
            return [
                'code'    => 400,
                'message' => 'request param [url_key,id] can not all empty',
                'data'    => [],
            ];
        } else if ($primaryKeyVal) {
           
            $category = Yii::$service->category->getByPrimaryKey($primaryKeyVal);
            if(isset($category['url_key']) && $category['url_key']){
                $data = $category;
            }
        } else if ($url_key) {
            $category = Yii::$service->category->getByUrlKey($url_key);
            if(isset($category['url_key']) && $category['url_key']){
                $data = $category;
            }
        }
        if (empty($data)) {
            
            return [
                'code'    => 400,
                'message' => 'can not find category by id or url_key',
                'data'    => [],
            ];
        } else {
            // ����mongodb����
            if (isset($data['_id'])) {
                $data = $data->attributes;
                $data['id'] = (string)$data['_id'];
                unset($data['_id']);
            }
            return [
                'code'    => 200,
                'message' => 'fetch category success',
                'data'    => $data,
            ];
        } 
    }
    /**
     * Add One Api������һ����¼��api
     */
    public function actionAddone(){
        //var_dump(Yii::$app->request->post());exit;
        // ѡ��
        $url_key            = Yii::$app->request->post('url_key');
        // ���� ������
        $title              = Yii::$app->request->post('title');
        // ѡ�� ������
        $meta_keywords      = Yii::$app->request->post('meta_keywords');
        // ѡ�� ������
        $meta_description   = Yii::$app->request->post('meta_description');
        // ���� ������
        $content            = Yii::$app->request->post('content');
        $status             = Yii::$app->request->post('status');
        if (!$title) {
            $error[] = '[title] can not empty';
        }
        if (!$content) {
            $error[] = '[content] can not empty';
        }
        if(!Yii::$service->fecshoplang->getDefaultLangAttrVal($title, 'title')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('title');
            $error[] = '[title.'.$defaultLangAttrName.'] can not empty';
        }
        if (!Yii::$service->fecshoplang->getDefaultLangAttrVal($content, 'content')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('content');
            $error[] = '[content.'.$defaultLangAttrName.'] can not empty';
        }
        if ($meta_keywords && !is_array($meta_keywords)) {
            $error[] = '[meta_keywords] must be array';
        }
        if ($meta_description && !is_array($meta_description)) {
            $error[] = '[meta_description] must be array';
        }
        if (!empty($error)) {
            
            return [
                'code'    => 400,
                'message' => 'data param format error',
                'data'    => [
                    'error' => $error,
                ],
            ];
        }
        $identity = Yii::$app->user->identity;
        $param = [
            'url_key'           => $url_key,
            'title'             => $title,
            'meta_keywords'     => $meta_keywords,
            'meta_description'  => $meta_description,
            'content'           => $content,
            'status'            => $status,
        ];
        $saveData = Yii::$service->cms->article->save($param, 'cms/article/index');
        return [
            'code'    => 200,
            'message' => 'add article success',
            'data'    => [
                'addData' => $saveData,
            ]
        ];
    }
    /**
     * Update One Api������һ����¼��api
     */
    public function actionUpdateone(){
        $id            = Yii::$app->request->post('id');
        // ѡ��
        $url_key            = Yii::$app->request->post('url_key');
        // ѡ�� ������
        $title              = Yii::$app->request->post('title');
        // ѡ�� ������
        $meta_keywords      = Yii::$app->request->post('meta_keywords');
        // ѡ�� ������
        $meta_description   = Yii::$app->request->post('meta_description');
        // ѡ�� ������
        $content            = Yii::$app->request->post('content');
        $status             = Yii::$app->request->post('status');
        if (!$id) {
            $error[] = '[id] can not empty';
        }
        if ($title && !Yii::$service->fecshoplang->getDefaultLangAttrVal($title, 'title')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('title');
            $error[] = '[title.'.$defaultLangAttrName.'] can not empty';
        }
        if ($content && !Yii::$service->fecshoplang->getDefaultLangAttrVal($content, 'content')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('content');
            $error[] = '[content.'.$defaultLangAttrName.'] can not empty';
        }
        if ($meta_keywords && !is_array($meta_keywords)) {
            $error[] = '[meta_keywords] must be array';
        }
        if ($meta_description && !is_array($meta_description)) {
            $error[] = '[meta_description] must be array';
        }
        if (!empty($error)) {
            return [
                'code'    => 400,
                'message' => 'data param format error',
                'data'    => [
                    'error' => $error,
                ],
            ];
        }
        $param = [];
        $identity = Yii::$app->user->identity;
        $url_key            ? ($param['url_key'] = $url_key)                    : '';
        $title              ? ($param['title'] = $title)                        : '';
        $meta_keywords      ? ($param['meta_keywords'] = $meta_keywords)        : '';
        $meta_description   ? ($param['meta_description'] = $meta_description)  : '';
        $content            ? ($param['content'] = $content)                    : '';
        $status             ? ($param['status'] = $status)                      : '';
        $primaryKey         = Yii::$service->cms->article->getPrimaryKey();
        $param[$primaryKey] = $id;
        $saveData = Yii::$service->cms->article->save($param, 'cms/article/index');
        return [
            'code'    => 200,
            'message' => 'add article success',
            'data'    => [
                'updateData' => $saveData,
            ]
        ];
    }
    /**
     * Delete One Api��ɾ��һ����¼��api
     */
    public function actionDeleteone(){
        $ids            = Yii::$app->request->post('ids');
        Yii::$service->cms->article->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!empty($errors)) {
            return [
                'code'    => 400,
                'message' => 'remove article by ids fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        } else {
            return [
                'code'    => 200,
                'message' => 'delete article success',
                'data'    => []
            ];
        }
    }
    
    
    /**
     * ���ڲ��Ե�action
     */
    public function actionTest()
    {
        $post = Yii::$app->request->post();
        return $post;
        //var_dump();exit;
        //var_dump(get_class(Yii::$service->cms->article->getByPrimaryKey('')));
    }
    
}
