<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Fecadmin\block\config;

use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
	public function init()
    {
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('fecadmin/config/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('fecadmin/config/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->admin->config;
        parent::init();
    }
	
	public function getLastData(){
		# 返回数据的函数
		# 隐藏部分
		$pagerForm = $this->getPagerForm();  
		# 搜索部分
		$searchBar = $this->getSearchBar();
		# 编辑 删除  按钮部分
		$editBar = $this->getEditBar();
		# 表头部分
		$thead = $this->getTableThead();
		# 表内容部分
		$tbody = $this->getTableTbody();
		# 分页部分
		$toolBar = $this->getToolBar($this->_param['numCount'],$this->_param['pageNum'],$this->_param['numPerPage']); 
		
		return [
			'pagerForm'	 	=> $pagerForm,
			'searchBar'		=> $searchBar,
			'editBar'		=> $editBar,
			'thead'		=> $thead,
			'tbody'		=> $tbody,
			'toolBar'	=> $toolBar,
		];
	}
	
	# 定义搜索部分字段格式
	public function getSearchArr(){
		$data = [
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'配置LABEL',
				'name'=>'label' ,
				'columns_type' =>'string'
			],
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'配置KEY',
				'name'=>'key' ,
				'columns_type' =>'string'
			],
		];
        
		return $data;
	}
	# 定义表格显示部分的配置
	public function getTableFieldArr(){
		$table_th_bar = [
			[	
				'orderField' 	=> 'id',
				'label'			=> 'ID',
				'width'			=> '40',
				'align' 		=> 'left',
			],
			[	
				'orderField'	=> 'label',
				'label'			=> '配置LABEL',
				'width'			=> '150',
				'align' 		=> 'left',
			],
			[	
				'orderField'	=> 'key',
				'label'			=> '配置key',
				'width'			=> '110',
				'align' 		=> 'left',
			],
			[	
				'orderField'	=> 'value',
				'label'			=> '配置值',
				'width'			=> '150',
				'align' 		=> 'left',
			],
			[	
				'orderField'	=> 'created_at',
				'label'			=> '创建时间',
				'width'			=> '70',
				'align' 		=> 'center',
			],
			[	
				'orderField'	=> 'updated_at',
				'label'			=> '更新时间',
				'width'			=> '70',
				'align' 		=> 'center',
			],
			[	
				'orderField'	=> 'created_person',
				'label'			=> '创建人',
				'width'			=> '50',
				'align' 		=> 'left',
			],
		];
        
		return $table_th_bar ;
	}
    
}