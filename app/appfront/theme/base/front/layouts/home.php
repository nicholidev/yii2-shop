<?php
$jsOptions = [
	# js config 1
	[
		'options' => [
			'position' =>  'POS_END',
		//	'condition'=> 'lt IE 9',
		],
		'js'	=>[
			'js/jquery-3.0.0.min.js',
			'js/js.js',
		],
	],
	# js config 2
	[
		'options' => [
			'condition'=> 'lt IE 9',
		],
		'js'	=>[
			'js/ie9js.js'
		],
	],
];

# css config
$cssOptions = [
	# css config 1.
	[
		'css'	=>[
			'css/style.css',
			'css/ie.css',
		],
	],
	
	# css config 2.
	[
		'options' => [
			'condition'=> 'lt IE 9',
		],
		'css'	=>[
			'css/ltie9.css',
		],
	],
];
\Yii::$service->page->asset->jsOptions 	= $jsOptions;
\Yii::$service->page->asset->cssOptions = $cssOptions;				
\Yii::$service->page->asset->register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en" id="sammyDress">
<head>
<?= Yii::$service->page->widget->render('head',$this); ?>
</head>
<body>
<?php $this->beginBody() ?>
	<header id="header">
		<?= Yii::$service->page->widget->render('header',$this); ?>
		<?= Yii::$service->page->widget->render('menu',$this); ?>
	</header>
	
	<div id="mainBox">
		<?= $content; ?>
	</div>
	<div class="footer-container">
		<?= Yii::$service->page->widget->render('footer',$this); ?>
	</div>
	<?= Yii::$service->page->widget->render('scroll',$this); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

