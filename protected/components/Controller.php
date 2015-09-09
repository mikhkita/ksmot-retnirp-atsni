<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='admin';
	public $scripts = array();
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

    public $interpreters = array();

    public $user;

    public $texts = NULL;

    public $start;
    public $render;
    public $debugText = "";

    public $adminMenu = array();
    public $settings = NULL;

    public function init() {
        parent::init();
        date_default_timezone_set("Asia/Novosibirsk");
        $this->user = User::model()->with("role")->findByPk(Yii::app()->user->id);

        $this->adminMenu["items"] = ModelNames::model()->findAll(array("order" => "sort ASC","condition" => "admin_menu=1"));

        foreach ($this->adminMenu["items"] as $key => $value) {
            $this->adminMenu["items"][$key] = $this->toLowerCaseModelNames($value);
        }

        $this->adminMenu["cur"] = $this->toLowerCaseModelNames(ModelNames::model()->find(array("condition" => "code = '".Yii::app()->controller->id."'")));
        
        $this->start = microtime(true);
    }

    public function beforeRender($view){
        parent::beforeRender($view);

        $this->render = microtime(true);

        $this->debugText = "Controller ".round(microtime(true) - $this->start,4);
        
        return true;
    }

    public function authPrinter(){
        $google = new GoogleCloudPrint();
        $google->refreshTokenConfig['refresh_token'] = '1/4a90S645OZW7X5VC6E5Zz7maNr4afKZahGs-Qbq47QQ';
        $token = $google->getAccessTokenByRefreshToken($google->urlconfig['refreshtoken_url'],http_build_query($google->refreshTokenConfig));
        $google->setAuthToken($token);
        return $google;
    }

    public function afterRenderPartial(){
        parent::afterRenderPartial();

        $this->debugText = ($this->debugText."<br>View ".round(microtime(true) - $this->render,4));
    }

    public function getUserRole(){
        return $this->user->role->code;
    }

    public function getUserRoleRus() {
    	return $this->user->role->name;
    }

    public function getUserRoleFromModel(){
        $user = User::model()->findByPk(Yii::app()->user->id);
        return $user->role->code;
    }

    public function getText($id,$params = NULL){
        if( $this->texts == NULL ) $this->getTexts();
        $output = "";

        if( isset($this->texts[(int)$id]) ){

            if( !Yii::app()->user->isGuest ){
                $class = ( $params != NULL && isset($params["class"]) )?(" ".$params["class"]):"";
                $attributes = ( $params != NULL && isset($params["reload"]) )?(' data-reload="true"'):"";
                $url = Yii::app()->createUrl('/text/adminupdate',array('id'=>$id,'json'=>'1'));
                $output .= '<font class="b-kit-update'.$class.'" href="'.$url.'" data-id="'.$id.'"'.$attributes.'>';
            }
            $this->texts[(int)$id] = str_replace("\n", "<br>", $this->texts[(int)$id]);
            $output .= $this->texts[(int)$id];

            if( !Yii::app()->user->isGuest ){
                $output .= '</font>';
            }
        }
        return $output;
    }

    public function getTexts(){
        $model = Text::model()->findAll();

        $this->texts = array();

        foreach ($model as $text) {
            $this->texts[$text->id] = $this->replaceToBr($text->text);
        }
    }

    public function getParam($code){
        if( $this->settings == NULL ) $this->getSettings();

        return $this->settings[mb_strtoupper($code,"UTF-8")];
    }

    public function getSettings(){
        $model = Settings::model()->findAll();

        $this->settings = array();

        foreach ($model as $param) {
            $this->settings[$param->code] = $param->value;
        }
    }

    public function toLowerCaseModelNames($el){
        if( !$el ) return false;
        $el->vin_name = mb_strtolower($el->vin_name, "UTF-8");
        $el->rod_name = mb_strtolower($el->rod_name, "UTF-8");

        return $el;
    }

    public function getRusMonth($num){
        $rus = array(
            "января",
            "февраля",
            "марта",
            "апреля",
            "мая",
            "июня",
            "июля",
            "августа",
            "сентября",
            "октября",
            "ноября",
            "декабря",
        );

        return $rus[$num-1];
    }

    public function replaceToBr($str){
        return str_replace("\n", "<br>", $str);
    }

    public function replaceToSpan($str){
        return "<span>".str_replace("<br>", "</span><span>", $str)."</span>";
    }

    public function getMenuCodes(){
        $model = Page::model()->findAllByPk(array(1,2),array("order"=>"pag_id ASC"));
        return $model;
    }
}