<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest; 
?>
<div class="main container two-columns-left">
    <?= Yii::$service->page->widget->render('breadcrumbs',$this); ?>
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2><?= Yii::$service->page->translate->__('My Favorite');?></h2>
				</div>
				<div style="width:100%;min-height:500px;">
					<div style="width:100%;">
						<?php if(is_array($coll) && !empty($coll)):  ?>
						<ul id="review_description" style="padding:0px;">
							<?php  foreach($coll as $one):  ?>
							<?php  $main_img = $one['image']['main']['image'];  ?>
							<li style="width:100%;min-height:160px;">
								<div class="review_description_left">
									<a target="_blank" href="<?= Yii::$service->url->getUrl($one['url_key'])  ?>">
									<p style="text-align:center;"><img src="<?= Yii::$service->product->image->getResize($main_img,[120,120],false) ?>"></p>
									</a>
								</div>
								<div class="review_description_right" style="width:600px;">
									<span class="review_description_right_span"><b>
										<a target="_blank" href="<?= Yii::$service->url->getUrl($one['url_key'])  ?>">
											<?= Yii::$service->store->getStoreAttrVal($one['name'],'name')  ?>
										</a>
									</span>
									<div class="review_description_centen">
										<div class="category_product" style="display:inline-block;float:left;">
											<?php
												$config = [
													'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\category\Price',
													'view'  		=> 'catalog/category/price.php',
													'price' 		=> $one['price'],
													'special_price' => $one['special_price'],
													'special_from' 	=> $one['special_from'],
													'special_to' 	=> $one['special_to'],
												];
												echo Yii::$service->page->widget->renderContent('category_product_price',$config);
											?>
										</div>
										<div class="favorite-Operation" style="display:inline-block;float:right; margin-top: 0px;">
											<a href='javascript:doPost("<?= Yii::$service->url->getUrl('customer/productfavorite') ?>", {"type":"remove", "favorite_id":"<?= $one['favorite_id'] ?>", "<?= CRequest::getCsrfName() ?>": "<?= CRequest::getCsrfValue() ?>" })' >
												<?= Yii::$service->page->translate->__('Delete');?>
											</a>
										</div>
										<div class="clear"></div>
										<div style="font-weight:100">
											<?= Yii::$service->page->translate->__('Favorite Date:');?><?= date('Y-m-d H:i:s',$one['updated_at']) ?>
										</div>
									</div>	
								</div>
							</li>
							<?php  endforeach;  ?>
						</ul>
						<?php  else:  ?>
							<?= Yii::$service->page->translate->__('You have no items in your favorite.');?>
						<?php  endif; ?>
						<?php if($pageToolBar): ?>
						<div class="pageToolbar">
							<label class="title">Page:</label><?= $pageToolBar ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-left ">
		<?php
			$leftMenu = [
				'class' => 'fecshop\app\appfront\modules\Customer\block\LeftMenu',
				'view'	=> 'customer/leftmenu.php'
			];
		?>
		<?= Yii::$service->page->widget->render($leftMenu,$this); ?>
	</div>
	<div class="clear"></div>
</div>
<script>
 function ShowRemark(eval,id){
        $('#remarkBox_'+id).show("slow");
    }
    function hideRemark(id){
         $('#remarkBox_'+id).hide("slow");
    }
    function ViledateForm(id){
        $val = $('#content_'+id).val();
        //alert($val);
        $url = $(eval).attr('url');
        $.ajax({
             type: "POST",
             data:"remark="+$val,
             url: "/favorite/product/remark?_id="+id,
             success: function(data){
                location.reload([true]);
             }
         });
    }
</script>