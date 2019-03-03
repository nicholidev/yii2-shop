<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container two-columns-left">
<?php // echo count($products); ?>
<?php  $count = 4; $end = $count-1; ?>
	<div class="col-main">
		<?= Yii::$service->page->widget->render('breadcrumbs',$this); ?>
		<div class="menu_category">
			<div class="category_img">
				<a href="#"><?=  $image ? '<img style="width:980px;" src="'.$image.'"/>' : '';?><a>
			</div>
			<div class="category_description">
				<h1><?=  $name ?></h1>
				<?=  $description ?>
			</div>
			<div class="panelBar">
				<?php  if(is_array($products) && !empty($products)): ?>
					<?php
						$parentThis = [
							'query_item' => $query_item,
							'product_page'=>$product_page,
						];
						$config = [
							'view'  		=> 'catalog/category/index/toolbar.php',
						];
						$toolbar = Yii::$service->page->widget->renderContent('category_toolbar',$config,$parentThis);
						echo $toolbar;
					?>
				<?php endif; ?>
			</div>
			<div class="category_product">
				<?php  if(is_array($products) && !empty($products)): ?>
					<?php $i = 0;  foreach($products as $product): ?>
						<?php  if($i%$count == 0): ?>
						<ul>
						<?php  endif; ?>
							<li>
                                <div style="display:none">
                                    <?= $currentOff = Yii::$service->product->price->currentOff; // 通过这个函数也可以得到特价折扣值  OFF ?>
                                </div>
								<div class="c_img">
									<a href="<?= $product['url'] ?>">
										<img class="js_lazy" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-original="<?= Yii::$service->product->image->getResize($product['image'],[230,230],false) ?>"  />
									</a>
								</div>
								<div class="c_name">
									<a href="<?= $product['url'] ?>">
										<?= $product['name'] ?>
									</a>
								</div>
								<?php
									$config = [
										'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\category\Price',
										'view'  		=> 'catalog/category/price.php',
										'price' 		=> $product['price'],
										'special_price' => $product['special_price'],
										'special_from' => $product['special_from'],
										'special_to' => $product['special_to'],
									];
									echo Yii::$service->page->widget->renderContent('category_product_price',$config);
								?>
							</li>
						<?php  if($i%$count == $end): ?>
						</ul>
						<?php  endif; ?>
						<?php  $i++; ?>
					<?php  endforeach;  ?>
					<?php  if($i%$count != $end): ?>
						</ul>
					<?php  endif; ?>
				<?php  endif;  ?>
			</div>
			<div class="clear"></div>
			<div class="panelBar">
				<?php  if(is_array($products) && !empty($products)): ?>
					<?php echo $toolbar; ?>
				<?php  endif;  ?>
			</div>
		</div>
	</div>
	<div class="col-left ">

		<?php
			# Refind By
			$parentThis = [
				'refine_by_info' => $refine_by_info,
			];
			$config = [
				'view'  		=> 'catalog/category/index/filter/refineby.php',
			];
			echo Yii::$service->page->widget->renderContent('category_product_filter_refine_by',$config,$parentThis);
		?>
		<?php
			# Category Left Filter subCategory
			$parentThis = [
				'filter_category' => $filter_category,
				'current_category'=> $name,
			];
			$config = [
				'view'  		=> 'catalog/category/index/filter/subcategory.php',
			];
			echo Yii::$service->page->widget->renderContent('category_product_filter_sub_category',$config,$parentThis);
		?>
		<?php
			# Category Left Filter Product Attributes
			$parentThis = [
				'filters' => $filter_info,
			];
			$config = [
				'view'  		=> 'catalog/category/index/filter/attr.php',
			];
			echo Yii::$service->page->widget->renderContent('category_product_filter_attr',$config,$parentThis);
		?>
		<?php
			# Category Left Filter Product Price
			$parentThis = [
				'filter_price' => $filter_price,
			];
			$config = [
				'view'  		=> 'catalog/category/index/filter/price.php',
			];
			echo Yii::$service->page->widget->renderContent('category_product_filter_price',$config,$parentThis);
		?>
	</div>
	<div class="clear"></div>
</div>
<script>
<?php $this->beginBlock('category_product_filter') ?>
$(document).ready(function(){
	$(".product_sort").change(function(){
		url = $(this).find("option:selected").attr('url');
		window.location.href = url;
	});
	$(".product_num_per_page").change(function(){
		url = $(this).find("option:selected").attr('url');
		window.location.href = url;
	});

	$(".filter_attr_info a").click(function(){
		if($(this).hasClass("checked")){
			$(this).removeClass("checked");
		}else{
			$(this).parent().find("a.checked").removeClass("checked");
			$(this).addClass("checked");
		}
	});
});
<?php $this->endBlock(); ?>
</script>
<?php $this->registerJs($this->blocks['category_product_filter'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
<?= Yii::$service->page->trace->getTraceCategoryJsCode($name_default_lang)  ?>