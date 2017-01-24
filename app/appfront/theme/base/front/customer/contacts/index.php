<div class="main container two-columns-left">
	<div class="col-main account_center">
		<?= Yii::$service->page->widget->render('flashmessage'); ?>
					
		<div class="std">
			<div class="page-title">
				<h2>Contact Information</h2>
			</div>
			<form method="post" id="form-validate" autocomplete="off">
				<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
				<div class="fieldset">
					<h2 class="legend">Contact Information</h2>
					<ul class="form-list">
						<li class="fields">
							<div class="customer-name">
								<div class="field name-firstname">
									<label for="firstname" class="required"><em>*</em>Name</label>
									<div class="input-box">
										<input id="contacts_name" name="editForm[name]" value="<?= $name ?>" title="First Name" maxlength="255" class="input-text required-entry" type="text">
									</div>
									<div style="height:10px;" id="contacts_name_span"></div>
								</div>
								<div class="field name-lastname">
									<label for="lastname" class="required"><em>*</em>Email</label>
									<div class="input-box">
										<input id="contacts_email" name="editForm[email]" value="<?= $email ?>" title="Last Name" maxlength="255" class="input-text required-entry" type="text">
									</div>
									<div style="height:10px;" id="contacts_email_span"></div>
										
								</div>
								<div class="clear"></div>
							</div>
						</li>
						<li>
							<label for="email" class="required">Telephone</label>
							<div class="input-box">
								<input name="editForm[telephone]" id="contacts_telephone" value="<?= $telephone ?>" title="Email Address" class="input-text required-entry validate-email" type="text">
								<span id="email_edit_span"></span>
							</div>
						</li>
						<li>
							<label for="email" class="required"><em>*</em>Comment</label>
							<div class="input-box">
								<textarea name="editForm[comment]" id="contacts_comment"><?= $comment ?></textarea>
								<span id="contacts_comment_span"></span>
							</div>
						</li>
						<?php  if($contactsCaptcha){  ?>
						<li>
							<label for="pass" class="required customertitle"><em>*</em>Verification code</label>
							<div class="input-box login_box">
								<input class="verification_code_input" maxlength="4" name="sercrity_code" value="" type="text">
									<img class="login-captcha-img"  title="click refresh" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
									<i class="refresh-icon"></i>
								<br>
								<script>
								<?php $this->beginBlock('login_captcha_onclick_refulsh') ?>  
								$(document).ready(function(){
									$(".refresh-icon").click(function(){
										$(this).parent().find("img").click();
									});
								});
								<?php $this->endBlock(); ?>  
								</script>  
								<?php $this->registerJs($this->blocks['login_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
							</div>
						</li>
						<?php  } ?>
					</ul>
				</div>
			<div class="buttons-set">
			   
				<!--
				<p class="back-link"><a href="http://10.10.10.252:3800/index.php/customer/account/"><small>? </small>Back</a></p>
				-->
				<button type="submit" title="Save" class="button" onclick="return check_contacts()"><span><span>Submit</span></span></button>
			</div>
		</form>
		</div>
	</div>
	
	<div class="col-left ">
		<address class="block contact-us-address-block">
			<div class="block-title">
				<h2>Contacts</h2>
			</div>
			<div class="block-content">
				<strong>Email:</strong> 
				<a href="mailto:<?= $contactsEmail ?>"><?= $contactsEmail ?></a>
				<br>
			</div>
		</address>
	</div>
	<div class="clear"></div>
</div>
	