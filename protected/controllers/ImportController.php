<?php

class ImportController extends Controller
{

	public function filters()
	{
		return array(
				'accessControl'
			);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('adminIndex','adminStep2','adminImport'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminIndex($partial = false)
	{
		$this->scripts[] = "import";
		$this->render('adminIndex',array(
			// 'model'=>$model
		));

	}
	

	public function actionAdminStep2($partial = false)
	{
		$this->scripts[] = "import";

		if(isset($_POST["excel_name"])) {

			$excel_path = Yii::app()->params['tempFolder']."/".$_POST["excel_name"];

			$xls = $this->getXLS($excel_path);

			$queryArr = array();

			$arr = array();
			for ($i=1; $i < count($xls); $i++) { 
				$markName = strtolower($xls[$i][1]);
				$modelName = strtolower($xls[$i][2]);

				if(!isset($arr[$markName])) $arr[$markName] = array("NAME"=>$xls[$i][1],"MODELS"=>array());
				if(!isset($arr[$markName]["MODELS"][$modelName])) $arr[$markName]["MODELS"][$modelName] = array("NAME"=>$xls[$i][2],"ENGINES"=>array());

				$engine = array();
				$str_to_del = array($xls[$i][1],$xls[$i][2]);
				$engine['NAME'] = trim(str_ireplace($str_to_del,"", $xls[$i][3]));
				$engine['HP'] = $xls[$i][4];

				array_push($arr[$markName]["MODELS"][$modelName]["ENGINES"], $engine);	

				array_push($queryArr, "name!='".$xls[$i][1]."'");			
			}

			if( count($arr) ){
				// Удаление марок, которых нет в экселе
				$model = Mark::model()->findAll(implode(" AND ", $queryArr));
				foreach ($model as $item) {
					$item->delete();
				}
			}

			$this->render('adminStep2',array(
				'arr'=>$arr,
				'excel_path'=>$excel_path
			));
		}
	}

	public function actionAdminImport(){
		if( isset($_POST["mark"]) ){
			$result = "success";
			$message = "Марка ".$_POST["mark"]." успешно ";

			$mark = Mark::model()->find("name='".$_POST["mark"]."'");

			if( isset($mark->id) ){
				foreach ($mark->models as $item) {
		        	$item->delete();
		        }
		        $message .= "обновлена";
			}else{
				$mark=new Mark;
				$mark->attributes = array("name"=>$_POST['mark']);
				if(!$mark->save()) die(json_encode(array("result"=>"error","message"=>"Ошибка добавления марки ".$_POST["mark"])));

				$message .= "добавлена";
			}

			foreach ($_POST["models"] as $key => $item) {
				$model = CarModel::model()->find("name='".addslashes($key)."'");

				if( !isset($model->id) ){
					$model=new CarModel;
					$model->attributes = array("name"=>addslashes($key),"mark_id"=>$mark->id);
					if(!$model->save()) die(json_encode(array("result"=>"error","message"=>"Ошибка добавления модели ".$key." у марки ".$_POST["mark"])));
				}

				if( !$this->setEngines($item,$model->id) ) die(json_encode(array("result"=>"error","message"=>"Ошибка добавления двигателей. Модели ".$key)));
			}
		}else{
			$result = "error";
			$message = "Отсутствует название марки";
		}

		// print_r(array("result"=>$result,"message"=>$message));
		echo json_encode(array("result"=>$result,"message"=>$message));
	}

	public function setEngines($arr = NULL, $model_id){
		if( !is_array($arr) || !is_numeric($model_id) ) return false;

		$tableName = Engine::tableName();
		$addVariants = array();

		$sql = "INSERT INTO `$tableName` (`model_id`,`name`,`horsepower`) VALUES ";
        foreach ($arr as $i => $engine){
        	$tmp = explode("|", $engine);

        	if( count($tmp) == 2 ){
        		if( !is_numeric($tmp[1]) ) return false;
        		$addVariants[] = "(".$model_id.", '".addslashes($tmp[0])."', '".$tmp[1]."')";
        	}else{
        		return false;
        	}
        }

        if( count($addVariants) > 0 ){
	        $sql .= implode(",", $addVariants);
	        if( !Yii::app()->db->createCommand($sql)->execute() ) return false;
		}

		return true;
	}
	
	public function loadModel($id)
	{
		$model=Import::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	private	function getXLS($xls,$rows = false,$titles = false){
		if( is_array($rows) && $titles === false )
			throw new CHttpException(404,'Отсутствуют наименования столбцов');

		include_once  Yii::app()->basePath.'/phpexcel/Classes/PHPExcel.php';
		include_once  Yii::app()->basePath.'/phpexcel/Classes/PHPExcel/IOFactory.php';
		
		$objPHPExcel = PHPExcel_IOFactory::load($xls);
		$objPHPExcel->setActiveSheetIndex(0);
		$aSheet = $objPHPExcel->getActiveSheet();
		
		$array = array();
		$cols = 1;

		for ($i = 1; $i <= $aSheet->getHighestRow(); $i++) {  
		    $item = array();
		    for ($j = 0; $j < $cols; $j++) {
		        $val = $aSheet->getCellByColumnAndRow($j, $i)->getCalculatedValue()."";

	        	// Этот кусок кода ограничивает матрицу по столбцам смотря на первую строку.
				// Если в первой строке 3 ячейки заполенных, 
				// то и во всех остальных он будет смотреть только по первым трем ячейкам.
		        if( !($val === "" && $i == 1) && $j < $cols ){
					array_push($item, ($val === "")?NULL:trim($val) );
					if( $i == 1 ) $cols++;
				}
		    }

		    // Если мы в переменной передаем массив отсортированных наименований столбцов
			// то происходит сортировка столбцов по этому массиву
			if(is_array($rows)) {
				$tmp = array();
				foreach ($rows as $key => $value) {
					if($value!="no-id") {
						if( $i == 1 ){
							array_push($tmp,$titles[intval($value)]["NAME"]);
						}else{
							array_push($tmp,$item[$key]);
						}
					}
				}
				$item=$tmp;
			}

			// Если нам нужна только первая строка
			if($rows === 1) return $item;

			array_push($array, $item);
		}
		return $array;
	} 


}
