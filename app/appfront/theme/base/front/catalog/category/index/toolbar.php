<?php   
	$query_item 	= $parentThis['query_item'];
	$product_page 	= $parentThis['product_page'];
?>
<div class="toolbar">
	<div class="tb_le">
		
		<?php  $frontSort = $query_item['frontSort']; ?>
		<?php if(is_array($frontSort) && !empty($frontSort)){ ?>
			<b>Sort By:</b>
			<select class="product_sort">	
				<?php foreach($frontSort as $np){   ?>
					<?php $selected = $np['selected'] ? 'selected="selected"' : ''; ?>
					<?php $url 		= $np['url'];  ?>
					<option <?= $selected; ?> url="<?= $url; ?>" value="<?= $np['value']; ?>"><?= $np['label']; ?></option>
				<?php } ?>
			</select>
		<?php } ?>
		<?php  $frontNumPerPage = $query_item['frontNumPerPage']; ?>
		<?php if(is_array($frontNumPerPage) && !empty($frontNumPerPage)){ ?>
			<select class="product_num_per_page">	
				<?php foreach($frontNumPerPage as $np){   ?>
					<?php $selected = $np['selected'] ? 'selected="selected"' : ''; ?>
					<?php $url 		= $np['url'];  ?>
					<option <?= $selected; ?> url="<?= $url; ?>" value="<?= $np['value']; ?>"><?= $np['value']; ?></option>
				<?php } ?>
			</select>
		<?php } ?>
	</div>
	<?= $product_page ?>
	<div class="clear"></div>
</div>
