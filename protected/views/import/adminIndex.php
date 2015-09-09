<h1>Импорт</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action' => Yii::app()->createUrl('/import/adminstep2'),
	'enableAjaxValidation'=>false,
	'id'=>'b-import-form'
)); ?>	
	<a href="#" data-path="<? echo Yii::app()->createUrl('/uploader/getForm',array('maxFiles'=>1,'extensions'=>'xls,xlsx', 'title' => 'Загрузка файла "Excel"', 'selector' => '.b-excel-input') ); ?>" class="b-get-image b-get-xls" ><img class="b-import-image" src="/images/excel.png" alt=""><span>Загрузить файл</span></a>
	<input type="hidden" name="excel_name" class="b-excel-input">
	<div>
		<a type="submit" href="#" id="b-next" onclick="$('#b-import-form').submit();" class="hidden b-import-butt b-butt">Импортировать</a>
	</div>
	<div class="inst">1) Формат загружаемого файла - Microsoft Excel (xls,xlsx).</div>
	<div class="inst">2) Столбцы в таблице должны располагаться в строгом порядке, образец приведен в таблице ниже.</div>
	<div class="inst">3) Первая строка в таблице должна содержать наименования (марка, модель, версия и т.д.), образец приведен в таблице ниже.</div>
	<div class="inst">4) Данные, которые есть в системе, но отсутствуют в файле импорта, будут удалены из системы.<br>
	Например: если в системе есть марка "Acura", а в импортируемом файле она отсутствует, то данная марка будет удалена. Аналогично с моделями и двигателями.</div>
	<table id="import-table" border="1">
		<tbody>
			<tr>
				<th>&nbsp;</th>
				<th>A</th>
				<th>B</th>
				<th>C</th>
				<th>D</th>
				<th>E</th>
				<th>F</th>
				<th>G</th>
				<th>H</th>
			</tr>
			<tr>
				<td>1</td>
				<td>Любые данные*</td>
				<td>Марка</td>
				<td>Модель</td>
				<td>Версия</td>
				<td>л.с.</td>
				<td>Любые данные*</td>
				<td>Любые данные*</td>
				<td>Любые данные*</td>
			</tr>
			<tr>
				<td>2</td>
				<td>Любые данные*</td>
				<td>Acura</td>
				<td>MDX</td>
				<td>Acura MDX 3.5i V6 24V</td>
				<td>240</td>
				<td>Любые данные*</td>
				<td>Любые данные*</td>
				<td>Любые данные*</td>
			</tr>
			<tr>
				<td>3</td>
				<td>Любые данные*</td>
				<td>Acura</td>
				<td>MDX</td>
				<td>Acura MDX 3.5i V6 24V</td>
				<td>263</td>
				<td>Любые данные*</td>
				<td>Любые данные*</td>
				<td>Любые данные*</td>
			</tr>
		</tbody>
	</table>
	<div class="inst">* Любые данные - данные, которые не учитываются при импорте.</div>
<?php $this->endWidget(); ?>


