<?php

class LandController extends Controller
{
	public $layout='//layouts/land';

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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex($partial = false)
	{	
		$images = array("name" => "автомобиль","car" => Yii::app()->request->baseUrl."/upload/images/default.png","logo" => "");
		if(isset($_SERVER['HTTP_REFERER'])) {
			$model = Mark::model()->findAll(array("order"=>"name"));
			foreach ($model as $key => $value) {
				$mark = explode(" ", $value->name);
				$last_word = array_pop($mark);
				$pos = strripos($_SERVER['HTTP_REFERER'], $last_word);
				if($pos) {
					// $value = Mark::model()->findbyPk($value->id);
					if($value->car!='') {
						$value->car = Yii::app()->request->baseUrl."/".$value->car;
						if($value->logo!='') $value->logo = Yii::app()->request->baseUrl."/".$value->logo;
						$images = array("name" => $value->name,"car" => $value->car,"logo" => $value->logo);		
					}
					break;
				}			
			}
		}

		try {
		 	Yii::app()->db->createCommand("SET OPTION SQL_BIG_SELECTS = 1")->execute();
		} catch (Exception $e) {}
		
		$model = Mark::model()->with(array('models.engines'))->findAll(array(
				'alias' => 'mark',
				'order' => 'mark.name ASC',
				'limit' => 1000
			));
		$this->render('index',array(
			'model' => $model,
			'images' => $images
		));
	}

	public function actionIndex2($partial = false)
	{	
		$images = array("name" => "автомобиль","car" => Yii::app()->request->baseUrl."/upload/images/default.png","logo" => "");
		if(isset($_SERVER['HTTP_REFERER'])) {
			$model = Mark::model()->findAll(array("order"=>"name"));
			foreach ($model as $key => $value) {
				$mark = explode(" ", $value->name);
				$last_word = array_pop($mark);
				$pos = strripos($_SERVER['HTTP_REFERER'], $last_word);
				if($pos) {
					// $value = Mark::model()->findbyPk($value->id);
					if($value->car!='') {
						$value->car = Yii::app()->request->baseUrl."/".$value->car;
						if($value->logo!='') $value->logo = Yii::app()->request->baseUrl."/".$value->logo;
						$images = array("name" => $value->name,"car" => $value->car,"logo" => $value->logo);		
					}
					break;
				}			
			}
		}
		$criteria=new CDbCriteria();
		$criteria->order = 'name ASC';
		// $criteria->with = array(
  //           // 'models',
  //           'models.engines'
  //           );

		$model = Mark::model()->findAll($criteria);
		$this->render('index2',array(
			'model' => $model,
			'images' => $images
		));
	}

	public function actionIndex3($partial = false)
	{	
		$images = array("name" => "автомобиль","car" => Yii::app()->request->baseUrl."/upload/images/default.png","logo" => "");
		if(isset($_SERVER['HTTP_REFERER'])) {
			$model = Mark::model()->findAll(array("order"=>"name"));
			foreach ($model as $key => $value) {
				$mark = explode(" ", $value->name);
				$last_word = array_pop($mark);
				$pos = strripos($_SERVER['HTTP_REFERER'], $last_word);
				if($pos) {
					// $value = Mark::model()->findbyPk($value->id);
					if($value->car!='') {
						$value->car = Yii::app()->request->baseUrl."/".$value->car;
						if($value->logo!='') $value->logo = Yii::app()->request->baseUrl."/".$value->logo;
						$images = array("name" => $value->name,"car" => $value->car,"logo" => $value->logo);		
					}
					break;
				}			
			}
		}
		$criteria=new CDbCriteria();
		$criteria->order = 'name ASC';
		// $criteria->with = array(
  //           // 'models',
  //           'models.engines'
  //           );

		$model = Mark::model()->findAll($criteria);
		$this->render('index3',array(
			'model' => $model,
			'images' => $images
		));
	}
			
	public function loadModel($id)
	{
		$model=Good::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function topMenu(){
		if( Yii::app()->user->isGuest ) return false;
		$this->render('topMenu',array(

		));
	}
}
