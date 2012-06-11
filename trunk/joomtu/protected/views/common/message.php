<?php 
if (Yii::app()->user->hasFlash('msgTitle')) {
	echo Yii::app()->user->getFlash('msgTitle');
} else {
	echo '提示';
}
?>
<br />
<?php 
if (Yii::app()->user->hasFlash('msgcontent')) {
	echo Yii::app()->user->getFlash('msgcontent');
} else {
	
}
?>
<br />
<?php if(Yii::app()->user->hasFlash('goUrl')){?>
<a href="<?php echo Yii::app()->user->getFlash('goUrl');?>">go</a>
<?php }else if(Yii::app()->getRequest()->getUrlReferrer()){?>
<a href="<?php echo Yii::app()->getRequest()->getUrlReferrer();?>">go</a>
<?php }else{?>
<a href="<?php echo Yii::app()->baseUrl;?>">go</a>
<?php }?>
