<?php

class EventController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminQueue','adminRefresh','adminDeletePhoto'),
				'roles'=>array('admin'),
			),
			array('allow',
				'actions'=>array('adminCheckPrint','adminGetImg','getTime'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminRefresh($event,$last_id,$limit)
	{
		echo json_encode($this->getItems($event,$last_id,$limit));
	}

	public function getItems($event_id,$last_id,$limit){
		$out = array();
		$model = Photo::model()->findAll(array('condition'=>'id>'.$last_id.' AND status=0 AND event_id='.$event_id.' AND print_time>'.time(),'limit'=>$limit));

		foreach ($model as $photo) {
			array_push($out, array(
				"id" => $photo->id,
				"image" => $photo->name,
				"time" => $photo->print_time
			));
		}

		return $out;
	}

	public function actionAdminDeletePhoto($id)
	{
		$model = Photo::model()->findByPk($id);
		$event_id = $model->event_id;

		$tableName = Photo::tableName();
		$sql = "UPDATE `$tableName` SET print_time = print_time-15 WHERE print_time>".$model->print_time;
		Yii::app()->db->createCommand($sql)->execute();

		if( $model && $model->status == 0 ){
			$model->status = 4;
			if( $model->save() ){
				echo json_encode(array("result" => "success", "items" => $this->getItems($event_id,0,21)));
			}else{
				echo json_encode(array("result" => "error", "message" => "Ошибка удаления: удаление не удалось"));
			}
		}else{
			if( $model->status != 4 )
				echo json_encode(array("result" => "error", "message" => "Ошибка удаления: фотография уже ушла на печать"));
		}
	}

	public function actionAdminQueue($id)
	{
		$model=$this->loadModel($id);

		if( $this->user->role->code != "root" && $model->user_id != $this->user->usr_id ) return false;

		$this->scripts[] = "queue";

		$this->render('adminQueue',array(
			'model'=>$model,
		));
	}

	public function actionGetTime(){
		echo time();
	}

	public function actionAdminCheckPrint(){
		Log::debug("Начало отправки на печать");
		$model = Photo::model()->with("event.printer")->findAll(array("condition"=>"print_time<".(time()+10)." AND status=0","order"=>"print_time ASC","limit"=>4));

		foreach ($model as $photo) {
			$photo->status = 1;
			$photo->save();
		}

		if( $model )
			Log::debug("Отправка на печать ".count($model)." элементов");

		$google = $this->authPrinter();

		foreach ($model as $photo) {
			$filename = $photo->name;
			$avatar_filename = $photo->user_photo;
			$username = $photo->user;
			$date = date("j")." ".$this->getRusMonth(date("n"))." ".date("Y");
			$hashtag = "#".$photo->event->hashtag;
			$location = $photo->event->location;
			$event_filename = $photo->event->logo;

			$generator = new ImageGenerator();

	    	$img_path = $generator->generate($filename,$avatar_filename,$username,$date,$hashtag,$location,$event_filename);

	    	if($this->printImage($google,$photo->event->printer->api_key,$img_path,$photo->event_id)){
	    		$photo->status = 2;
	    		Log::debug("ID ".$photo->id." успешно отправилось на печать");
	    	}else{
	    		$photo->status = 3;
	    		Log::debug("ID ".$photo->id." ошибка отправки на печать");
	    	}

	    	unlink($img_path);

			$photo->save();
		}
		Log::debug("Кончало отправки на печать");
	}

	public function printImage($google,$printer_id,$img_path,$event_id){
		$printers = $google->getPrinters();

		$printerid = "";
		if(count($printers)==0) {
			
			echo "Could not get printers";
			exit;
		}
		else {
			
			$printerid = $printers[0]['id'];

			$resarray = $google->sendPrintToPrinter($printer_id, $event_id." ".$img_path, $img_path, "image/jpeg");
			
			if($resarray['status']==true) {
				Log::debug("Отправлено успешно");
			}
			else {
				Log::debug("Ошибка отправки: ".$resarray['errorcode']);
				// echo "An error occured while printing the doc. Error code:".$resarray['errorcode']." Message:".$resarray['errormessage'];
			}
		}

		return $resarray['status'];
	}

	public function actionAdminCreate()
	{
		$model=new Event;
		if(isset($_POST['Event']))
		{
			foreach ($_POST['Event'] as &$value) {
		    	$value = trim($value);
			}
			if( !isset($_POST["Event"]["user_id"]) ) $_POST["Event"]["user_id"] = $this->user->usr_id;
			$_POST['Event']['logo'] = $this->setImage($_POST['Event']['logo']);
			$_POST['Event']['start_time'] = date_timestamp_get(date_create($_POST['Event']['start_time']));
			$model->attributes = $_POST['Event'];
			if($model->save()){
				$this->actionAdminIndex(true);
				return true;
			}

		} else {
			$this->renderPartial('adminCreate',array(
				'model'=>$model
			));
		}
	}

	public function actionAdminUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Event']))
		{
			foreach ($_POST['Event'] as &$value) {
		    	$value = trim($value);
			}
			if($model->logo != $_POST['Event']['logo']) {
				unlink($model->logo);
				$_POST['Event']['logo'] = $this->setImage($_POST['Event']['logo']);
			}
			$_POST['Event']['start_time'] = date_timestamp_get(date_create($_POST['Event']['start_time']));
			$model->attributes=$_POST['Event'];
			if($model->save())
				$this->actionAdminIndex(true);
		}else{
			$model->start_time = date('d.m.Y H:i',$model->start_time);
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{	
		$model=$this->loadModel($id);
		unlink($model->logo);

		$google = $this->authPrinter();

		$model->delete();

		$google->deleteJobs($id);

		$this->actionAdminIndex(true);
	}

	public function actionAdminIndex($partial = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}
		$filter = new Event('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Event']))
        {
            $filter->attributes = $_GET['Event'];
            foreach ($_GET['Event'] AS $key => $val)
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
        
		$model = Event::model()->findAll($criteria);

        $options = array(
			'data'=>$model,
			'filter'=>$filter,
			'labels'=>Event::attributeLabels()
		);
		if( !$partial ){
			$this->render('adminIndex',$options);
		}else{
			$this->renderPartial('adminIndex',$options);
		}

	}

	public function actionAdminGetImg()
	{
		Log::debug("Начало парсинга");
		
		$events = Event::model()->findAll();

		if($events) foreach ($events as $model) {
		
			$event_id = $model->id;
			$event_tag = $model->hashtag;
			$start_time = intval($model->start_time); 	
			$current_time = time() + $model->offset;
		    $delay = ($model->offset == 0) ? 0 : 15;

		   	$criteria = new CDbCriteria();
			$criteria->order = "id DESC";
			$condition = "event_id=".$event_id;
	
			$criteria->condition = $condition;
			$model_time = Photo::model()->find($criteria);		
			if($model_time) {
				$current_time = ( intval($model_time->print_time) > $current_time ) ? $model_time->print_time : $current_time;	
			}	

			/* VK */
		    $vk_url = "https://api.vk.com/method/photos.search";
			$params = array(
		    	'q' => '%23'.$event_tag,
		    	'v' => 5.37,
		    	'sort' => 0
			);
			$vk_url .='?'.urldecode(http_build_query($params));

			$criteria->condition = $condition." AND social_id=1";
			$model = Photo::model()->find($criteria);
			
			if($model) {

				$current_time = $this->vk_parse($event_id,$current_time,$delay,$vk_url,$start_time,40,true);

			} else {
				
				$current_time = $this->vk_parse($event_id,$current_time,$delay,$vk_url,$start_time,500);

			}

			/* INSTAGRAM */
			$inst_url = "https://api.instagram.com/v1/tags/".$event_tag."/media/recent";
			$params = array(
		    	'count' => 33,
		    	'access_token' => '2160403451.a28fb91.d3ed016a2a5c4e33bda3b3347e167623'
			);
			$inst_url .='?'.urldecode(http_build_query($params));
			
			$criteria->condition = $condition." AND social_id=2";
			$model = Photo::model()->find($criteria);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
			if($model) {	

				$inst_url .= "&MIN_TAG_ID=".$model->soc_key;
				curl_setopt($ch, CURLOPT_URL, $inst_url); 
		   	 	$temp = json_decode(curl_exec($ch));
				$instagram = $temp->data;

				if(!empty($instagram)) {							
					for ($i = count($instagram)-1; $i >= 0; $i--) { 
						$name = $instagram[$i]->images->standard_resolution->url;
						if( !Photo::model()->exists("name='".$name."' AND social_id=2 AND event_id=".$event_id) && intval($instagram[$i]->created_time) >= $start_time ) {
							$current_time += $delay;
							$model = new Photo;
							$model->name = $name; 
							$model->user = $instagram[$i]->user->username;
							$model->user_photo = $instagram[$i]->user->profile_picture;
							$model->soc_key = substr($instagram[$i]->id,0,19);
							$model->social_id = "2";
							$model->event_id = $event_id;
							$model->print_time = $current_time;
							$model->save();     
						}
					} 
				}

			} else {
				$instagram = array();
				$time = $start_time;
				while ($time >= $start_time) {	
					curl_setopt($ch, CURLOPT_URL, $inst_url); 
		   	 		$temp = json_decode(curl_exec($ch));
					if(!empty($temp->data)) {
						foreach ($temp->data as $item) {	
							if(intval($item->created_time) >= $time) array_push($instagram, $item);	else $time = $start_time-1;
						}
						if(isset($temp->pagination->next_url)) $url = $temp->pagination->next_url; else $time = $start_time-1;	
					} else {
						$time = $start_time-1;
					}	
					if(count($instagram) > 500) $time = $start_time-1;			
				}
				for ($i = count($instagram)-1; $i >= 0; $i--) { 
					$current_time += $delay;
					$model = new Photo;
					$model->name = $instagram[$i]->images->standard_resolution->url;
					$model->user = $instagram[$i]->user->username;
					$model->user_photo = $instagram[$i]->user->profile_picture;
					$model->soc_key = substr($instagram[$i]->id,0,19);
					$model->social_id = "2";
					$model->event_id = $event_id;
					$model->print_time = $current_time;  
					$model->save(); 
					
				} 
			}
			curl_close($ch);

		}
		Log::debug("Кончало парсинга");

		$this->actionAdminCheckPrint();
	}

	public function vk_parse($event_id,$current_time,$delay,$url,$start_time,$count,$check = false) {

		$params = array(
		    'start_time' => $start_time,
		    'count' => $count
		);
		$url .="&".urldecode(http_build_query($params));
		$temp = json_decode(file_get_contents($url)); 
   	 	$vk = $temp->response->items;
   	 	
   	 	if(!empty($vk)) { 
	   	 	$vk_users = array();
	   	 	$vk_groups = array();
	   	 	$photo = array();
	   	 	$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);   

	   	 	for ($i = count($vk)-1; $i >= 0; $i--) { 
	   	 		$pos = strpos($vk[$i]->owner_id, "-");
	   	 		if( $pos === false) {
	   	 			$vk_users[$vk[$i]->owner_id] = $vk[$i]->owner_id;
	   	 		} else {
	   	 			$vk[$i]->owner_id = substr($vk[$i]->owner_id, 1);
	   	 			$vk_groups[$vk[$i]->owner_id] = $vk[$i]->owner_id;
	   	 		}
	   	 	}
	  
	   	 	if(!empty($vk_users)) {
		   	 	$url = "https://api.vk.com/method/users.get?fields=photo_100&user_ids=".implode(",",$vk_users);
		   	 	curl_setopt($ch, CURLOPT_URL, $url); 
		   	 	$temp = json_decode(curl_exec($ch));
		   	 	if(isset($temp->response)) {
			   	 	foreach ($temp->response as $item) {
			   	 		$photo[$item->uid] = $item->photo_100;
			   	 	}
		   	 	}
	   	 	}
	   	 	if(!empty($vk_groups)) {
				$url = "https://api.vk.com/method/groups.getById?fields=photo_100&group_ids=".implode(",",$vk_groups);
		   	 	curl_setopt($ch, CURLOPT_URL, $url); 
		   	 	$temp = json_decode(curl_exec($ch));
		   	 	if(isset($temp->response)) {
			   	 	foreach ($temp->response as $item) {
			   	 		$photo[$item->gid] = $item->photo_100;
			   	 	}
			   	}
	   	 	}
	   	 	curl_close($ch);

			for ($i = count($vk)-1; $i >= 0; $i--) { 
				$name = $vk[$i]->photo_604;
				if( $check === false || !Photo::model()->exists("name='".$name."' AND social_id=1 AND event_id=".$event_id) ) {
					$current_time += $delay;
					$model = new Photo;
					$model->name = $name;
					$model->user = "id".$vk[$i]->owner_id;
					if(isset($photo[$vk[$i]->owner_id])) {
						$model->user_photo = $photo[$vk[$i]->owner_id];
					} else $model->user_photo = "http://vk.com/images/camera_b.gif";
					$model->soc_key = $vk[$i]->date;
					$model->social_id = "1";
					$model->event_id = $event_id;
					$model->print_time = $current_time;  
					$model->save();
					$check = false;
				}
			}
		}
		return $current_time;
	}

	public function loadModel($id)
	{
		$model=Event::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function setImage($name){
		$arr = explode("/", $name);
		$name = array_pop($arr);

		$tmpFileName = Yii::app()->params['tempFolder']."/".$name;
		$fileName = Yii::app()->params['imageFolder']."/".$name;

		$resizeObj = new Resize($tmpFileName);
		$resizeObj -> resizeImage(200, 200, 'auto');
		$resizeObj -> saveImage($fileName, 100);

		unlink($tmpFileName);
		return $fileName;
	}	
}
