<?php
# ���ļ���app/web/index.php �����롣
# 
# fecshop - appfront �ĺ���ģ��
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename){
	$modules = array_merge($modules,require($filename));
}
# �˴�Ҳ������дfecshop������������á�
return [
	'modules'=>$modules,
	/* only config in front web */
	'bootstrap' => ['store'],
];
