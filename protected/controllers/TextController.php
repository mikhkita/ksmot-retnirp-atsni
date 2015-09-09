<?php

class TextController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminAdd'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate()
	{
		$model=new Text;
		if(isset($_POST['Text']))
		{
			$model->attributes=$_POST['Text'];
			if($model->save()){
				$this->actionAdminIndex(true);
				return true;
			}
		}
		$this->renderPartial('adminCreate',array(
			'model'=>$model
		));

	}

	public function actionAdminUpdate($id,$json = false)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Text']))
		{
			$model->attributes=$_POST['Text'];
			if($model->save()){
				if($json !== false){
					echo json_encode(array("result"=>"updated","id"=>$model->id,"text"=>$this->replaceToBr($model->text)));
				}else{
					$this->actionAdminIndex(true);
				}
			}else{
				if( $json !== false) echo json_encode(array("result"=>"error","error"=>"Ошибка при сохранении"));
			}
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
		$filter = new Text('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Text']))
        {
            $filter->attributes = $_GET['Text'];
            foreach ($_GET['Text'] AS $key => $val)
            {
                if ($val != '')
                {
                    if( $key == "name" ){
                    	$criteria->addSearchCondition('name', $val);
                    }else{
                    	$criteria->addCondition("$key = '{$val}'");
                    }
                }
            }
        }

        $criteria->order = 'name ASC';

        $model = Text::model()->findAll($criteria);
        $options = array(
			'data'=>$model,
			'filter'=>$filter,
			'labels'=>Text::attributeLabels()
		);
		if( !$partial ){
			$this->render('adminIndex',$options);
		}else{
			$this->renderPartial('adminIndex',$options);
		}

	}

	public function loadModel($id)
	{
		$model=Text::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
