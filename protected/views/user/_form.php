<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_name'); ?>
		<?php echo $form->textField($model,'usr_name',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'usr_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_login'); ?>
		<?php echo $form->textField($model,'usr_login',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'usr_login'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_password'); ?>
		<?php echo $form->passwordField($model,'usr_password',array('size'=>60,'maxlength'=>128,'required'=>true)); ?>
		<?php echo $form->error($model,'usr_password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usr_email'); ?>
		<?php echo $form->textField($model,'usr_email',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'usr_email'); ?>
	</div>

	<?php if( Yii::app()->user->checkAccess('createUser') ):?>
		<div class="row">
			<?php echo $form->labelEx($model,'usr_rol_id'); ?>
			<?php echo $form->dropDownList($model, 'usr_rol_id', CHtml::listData(Role::model()->findAll(), 'id', 'name')); ?>
			<?php echo $form->error($model,'usr_rol_id'); ?>
		</div>
	<?endif;?>

	<?
		$printerCount = array();
		for ($i=1; $i <= 10; $i++) { 
			$printerCount[$i.""] = $i."";
		}
		$printerCount["999"] = "Без ограничений";
	?>

	<?php if( Yii::app()->user->checkAccess('createUser') ):?>
		<div class="row">
			<?php echo $form->labelEx($model,'usr_printer_count'); ?>
			<?php echo $form->dropDownList($model, 'usr_printer_count', $printerCount); ?>
			<?php echo $form->error($model,'usr_printer_count'); ?>
		</div>
	<?endif;?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->