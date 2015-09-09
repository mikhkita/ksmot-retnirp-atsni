<div class="b-import">
    <h1>Импорт</h1>
    <div class="progress">
        <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:3%">3%</div>
    </div>
    <ul class="b-log"></ul>
</div>
<div class="b-preview" style="display: none;" data-url="<?=Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminimport')?>">
	<table class="b-table b-import-preview-table" border="1">
        <? foreach ($arr as $mark_name => $mark): ?>
            <tr> 
                <td>
                    <input type="hidden" name="mark" value="<?=$mark["NAME"]?>">           
                    <? foreach ($mark["MODELS"] as $model_name => $model): ?>
                        <? foreach ($model["ENGINES"] as $i => $engine): ?>
                            <input type="hidden" name="models[<?=$model["NAME"]?>][]" value="<?=htmlspecialchars($engine['NAME'])?>|<?=$engine['HP']?>">
                        <? endforeach ?> 
                    <? endforeach ?>   
                </td> 
            </tr>
        <? endforeach ?>
    </table>
	<!-- <div class="row buttons">
		<?php echo CHtml::submitButton('Перейти к предпросмотру'); ?>
		<input type="hidden" name="excel_path" value="<?=$excel_path?>">
		<input type="button" value="Отменить">
	</div> -->
</div>