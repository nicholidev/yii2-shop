<?php
    use fec\helpers\CRequest;
?>
<h1>完成安装</h1>
<br/>

<?=  $errorInfo  ?>
<?=  $successInfo  ?>

<br/>
<br/>

<?php echo CRequest::getCsrfInputHtml();  ?>
<p style="font-size:16px;line-height:25px;">您还需要进行如下的步骤：</p>

<p style="font-size:16px;line-height:25px;">1.需要设置安全权限（根目录执行，win不需要执行）：<span style="color:#c7254e">chmod 644 common/config/main-local.php</span></p>

<p style="font-size:16px;line-height:25px;">2.删除安装文件 <span style="color:#c7254e">install.php</span>（非必须执行，安装完成后访问install.php会被阻止执行，不过删除掉这个文件会处理的更彻底）(文件路径为：appfront/web/install.php),  </p>

<p style="font-size:16px;line-height:25px;">3.访问后台（访问nginx配置的后台appadmin域名),  继续进行安装配置。 </p>


<br/>
<br/>

