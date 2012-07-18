<?php
$this->pageTitle = 'User - ' . Yii::app()->name;
$this->breadcrumbs = array('User');
?>
<p><?php echo $user->name; ?></p>
<?php if (!empty($toMeRels)) { ?>
    <p>She/He is my <?php echo implode(',', $toMeRels) ?></p>
    <?php
}?>