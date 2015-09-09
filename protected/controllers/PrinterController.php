<?php

class PrinterController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminRemoveQueue'),
				'roles'=>array('admin'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate()
	{
		$model=new Printer;
		if(isset($_POST['Printer']))
		{
			foreach ($_POST['Printer'] as &$value) {
		    	$value = trim($value);
			}
			if( !isset($_POST["Printer"]["user_id"]) ) $_POST["Printer"]["user_id"] = $this->user->usr_id;
			$model->attributes=$_POST['Printer'];
			if($model->save()){
				$this->actionAdminIndex(true);
				return true;
			}
		}
		$this->renderPartial('adminCreate',array(
			'model'=>$model
		));

	}

	public function actionAdminRemoveQueue($api_key){
		$google = $this->authPrinter();
		$google->deleteJobsByApi($api_key);
		echo "<script>window.location.reload();</script>";
	}

	public function actionAdminUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Printer']))
		{
			foreach ($_POST['Printer'] as &$value) {
		    	$value = trim($value);
			}
			$model->attributes=$_POST['Printer'];
			if($model->save())
				$this->actionAdminIndex(true);
		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->actionAdminIndex(true);
	}

	public function actionAdminIndex($partial = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}
		$filter = new Printer('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Printer']))
        {
            $filter->attributes = $_GET['Printer'];
            foreach ($_GET['Printer'] AS $key => $val)
            {
                if ($val != '')
                {
                    if( $key == "name" || $key == "api_key" ){
                    	$criteria->addSearchCondition($key, $val);
                    }else{
                    	$criteria->addCondition("$key = '{$val}'");
                    }
                }
            }
        }
        if( $this->user->role->code != "root" ){
        	$criteria->addCondition("user_id=".$this->user->usr_id);
        }

        $criteria->order = 'name ASC';
        
		$model = Printer::model()->findAll($criteria);

		$google = $this->authPrinter();

		$queue_count = $google->getQueueCount();

        $options = array(
			'data'=>$model,
			'filter'=>$filter,
			'labels'=>Printer::attributeLabels(),
			'queue_count'=>$queue_count
		);
		if( !$partial ){
			$this->render('adminIndex',$options);
		}else{
			$this->renderPartial('adminIndex',$options);
		}

	}

	public function loadModel($id)
	{
		$model=Printer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
