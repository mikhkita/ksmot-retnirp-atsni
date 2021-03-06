<?

Class Log {
    function __construct() {

    }

    public function set($filename,$message,$error = false){
        $string = date("Y-m-d H:i:s", time())." ";
        $string .= ( ( $error === true )?"Ошибка":( ($error === false)?"Сообщение":$error ) ).": ";
        $string .= $message;

        file_put_contents(Yii::app()->basePath."/logs/".$filename.".txt", $string."\n", FILE_APPEND);

        if( $error === true )
            file_put_contents(Yii::app()->basePath."/logs/errors.txt", $string."\n", FILE_APPEND);
    }

    public function setSniper($message,$error = false){
        Log::set("sniper",$message,$error);
    }

    public function sniper($message,$error = false){
        Log::set("sniper",$message,$error);
    }

    public function debug($message,$error = false){
        Log::set("debug",$message,$error);
    }

}

?>