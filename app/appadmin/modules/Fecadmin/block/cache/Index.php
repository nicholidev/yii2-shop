<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Fecadmin\block\cache;
use fecadmin\FecadminbaseBlock;
use fecadmin\models\AdminUser;
use fecadmin\models\AdminLog;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fec\helpers\CCache;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends FecadminbaseBlock{
	public $_obj ;
	public $_paramKey = 'id';
	public $_defaultDirection = 'asc';
	public $_currentUrl;
	
	public function __construct(){
		$this->_currentUrl = CUrl::getUrl("fecadmin/cache/index");
		$this->_modelName = 'admin_user';
		parent::__construct();
	}
	// 各个入口的redis配置和common的redis配置，合并，最后存放到该类变量中
    public $appRedisCache;
    // 报错信息
    public $errors; 
    // 从模块配置中取出来 common的redis配置的数组key。
    public $commonConfigKey = 'commonConfig';
	
	# 初始化参数
	public function initParam(){
		# 定义编辑和删除的URL
		
		$this->_editUrl 	= ''; #CUrl::getUrl("fecadmin/log/indexedit");
		$this->_deleteUrl 	= '';	#CUrl::getUrl("fecadmin/account/indexdelete");
		$this->_obj	= new AdminLog;
		$this->_paramKey = 'id';
		/*  
		# 自定义参数如下：
		#排序默认为主键倒序
		$this->_orderField  = 'created_at';
		$this->_sortDirection = 'asc';
		
		# 主键默认为id
		$this->_paramKey = 'id';
		
		#第一次打开默认为第一页,一页显示50个
		$this->_pageNum = 1;
		$this->_numPerPage;
		
		*/
		parent::initParam();
		
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
		
		];
		return $data;
	}
	
	public function getEditBar(){
		if(!strstr($this->_currentParamUrl,"?")){
			$csvUrl = $this->_currentParamUrl."?type=export";
		}else{
			$csvUrl = $this->_currentParamUrl."&type=export";
		}

		return '<ul class="toolBar">
					<li><a title="确实要刷新?" target="selectedTodo" rel="ids" postType="string" href="'.$this->_currentUrl.'?method=reflush" class="edit"><span>刷新缓存</span></a></li>
					<li class="line">line</li>
				</ul>';
	}
	
	public function getTableThead(){
		return '
			<thead>
				<tr>
					<th width="22"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
					<th width="40">Cache名称</th>
					<th width="110">Cache描述</th>
				</tr>
			</thead>';
	}
	
	public function getTableTbody(){
		$str = '';
        $reflushRedisCache = \Yii::$app->controller->module->params['cacheRedisConfigFile'];
        if (is_array($reflushRedisCache)) {
            foreach ($reflushRedisCache as $appName => $c) {
                if ($appName != $this->commonConfigKey) {
                    $str .= '<tr target="sid_user" rel="'.$appName.'">
                        <td><input name="ids" value="'.$appName.'" type="checkbox"></td>
                        <td>'.$appName.'</td>
                        <td>刷新入口全部缓存：'.$appName.'</td>
                    </tr>
                    ';
                }
            }
        }
		
		return	$str;
	}
    /**
     * 根据模块的配置部分，得到各个入口的redis的配置
     * 原理：根据配置中指定的各个入口的redis所在的配置文件，取出来各个入口的redis配置，
     *       然后和common的redis配置合并，得到入口最终的redis配置，然后实例化redis component，然后清空redis缓存
     */
    public function getRedisCacheConfig(){
        $arr = \Yii::$app->controller->module->params['cacheRedisConfigFile'];
        if (is_array($arr)) {
            // 加载common公用基础redis配置
            if (!isset($arr[$this->commonConfigKey]) || !$arr[$this->commonConfigKey]) {
                $this->errors = 'module config: cacheRedisConfigFile[commonConfig] can not empty';
                
                return false;
            }
            $file = Yii::getAlias($arr[$this->commonConfigKey]);
            $config = require($file);
            if (!isset($config['components']['cache']['redis']) || !$config['components']['cache']['redis']) {
                $this->errors = 'can not find  $config[\'components\'][\'cache\'][\'redis\'] in '.$file;
                
                return false;
            }
            
            if (!isset($config['components']['redis']['class']) || !$config['components']['redis']['class']) {
                $this->errors = 'can not find  $config[\'components\'][\'redis\'][\'class\'] in '.$file;
                
                return false;
            }
            
            $baseConfig = isset($config['components']['cache']['redis']) ? $config['components']['cache']['redis'] : [];
            $redisClass = $config['components']['redis']['class'];
            $baseConfig['class'] = $redisClass;
            // 加载各个入口的redis配置
            foreach ($arr as $app => $appFile) {
                if ($app != $this->commonConfigKey) {
                    $file = Yii::getAlias($appFile);
                    $config = require($file);
                    $appRedisConfig = isset($config['components']['cache']['redis']) ? $config['components']['cache']['redis'] : [];
                    if (!empty($appRedisConfig)) {
                        $this->appRedisCache[$app] = \yii\helpers\ArrayHelper::merge($baseConfig, $appRedisConfig);
                    }
                }
            }
        }
        return true;
    }
    /**
     * 清空选择的入口的所有缓存。
     */
	public function reflush(){
        if (!$this->getRedisCacheConfig()) {
            echo  json_encode([
                "statusCode"    => "300",
                "message"       => $this->errors,
            ]);
            exit;
        }
        $successReflushAppNameArr = [];
		$cacheAppNameStr = Yii::$app->request->get('ids');
		$cacheAppNameArr = explode(",",$cacheAppNameStr);
        if (is_array($cacheAppNameArr)) {
            foreach ($cacheAppNameArr as $cacheAppName) {
                $cacheAppName = trim($cacheAppName);
                if (isset($this->appRedisCache[$cacheAppName]) && $this->appRedisCache[$cacheAppName]) {
                    $redisComponent = Yii::createObject($this->appRedisCache[$cacheAppName]);
                    $redisComponent->executeCommand('FLUSHDB');
                    $successReflushAppNameArr[] = $cacheAppName;
                }
            }
        }
		# 刷新 配置 缓存
		// \fecadmin\helpers\CConfig::flushCacheConfig();
        echo  json_encode([
            "statusCode"=>"200",
            "message"=>"reflush cache success, appName:".implode(',',$successReflushAppNameArr),
        ]);
        exit;
    }
}






