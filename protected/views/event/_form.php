<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faculties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hashtag'); ?>
		<?php echo $form->textField($model,'hashtag',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'hashtag'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'location'); ?>
		<?php echo $form->textField($model,'location',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'location'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_time'); ?>
		<?php echo $form->textField($model,'start_time',array('maxlength'=>255,'required'=>true)); ?>
		<?php echo $form->error($model,'start_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'offset'); ?>
		<?php echo $form->textField($model,'offset',array('maxlength'=>255,'required'=>true,'class'=>'numeric')); ?>
		<?php echo $form->error($model,'offset'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'printer_id'); ?>
		<?php echo $form->dropDownList($model, 'printer_id', CHtml::listData(Printer::model()->findAll(array('order'=>'name ASC','condition'=>'user_id='.$this->user->usr_id)), 'id', 'name') ,array('required'=>true)); ?>
		<?php echo $form->error($model,'printer_id'); ?>
	</div>

	<div class="row b-logo-image">
		<?php echo $form->labelEx($model,'logo'); ?>
		<div class="b-image-cancel">Отменить удаление</div>
		<div class="b-image-cont">
			<div data-path="<? echo Yii::app()->createUrl('/uploader/getForm',array('maxFiles'=>1,'extensions'=>'jpg,png,bmp', 'title' => 'Загрузка логотипа', 'selector' => '.b-input-image', 'tmpPath' => Yii::app()->params['tempFolder']) ); ?>" class="b-input-image-add b-get-image<? if( $model->logo != "" ) echo " hidden"; ?>" title="Добавить изображение"></div>
			<div class="b-image-wrap<? if( $model->logo == "" ) echo " hidden"; ?>">
				<div class="b-input-image-img" data-base="<? echo Yii::app()->request->baseUrl; ?>" style="background-image: url('<? echo (Yii::app()->request->baseUrl)."/".($model->logo); ?>');"></div>
				<?php echo $form->textField($model,'logo',array('required'=>true,'class'=>'b-input-image')); ?>
				<?php echo $form->error($model,'logo'); ?>
				<div class="b-image-controls clearfix">
					<div class="b-image-nav b-image-edit b-get-image" title="Изменить изображение"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
		<input type="button" onclick="$.fancybox.close(); return false;" value="Отменить">
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->