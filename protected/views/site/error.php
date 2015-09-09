<?php
$this->pageTitle=Yii::app()->name . ' - Ошибка';
$this->breadcrumbs=array(
	'Error',
);

$error=Yii::app()->errorHandler->error;

?>
<h1>Ошибка</h1>

<h2>Error <?php echo $code; ?></h2>
<div class="error">
<?php echo $error["message"]."<br>".$error["file"]." (".$error["line"].")<br>".$error["trace"]; ?>
</div>