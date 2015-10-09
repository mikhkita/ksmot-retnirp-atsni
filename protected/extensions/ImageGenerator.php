<?php

define('PHOTO_WIDTH_PERCENT', 94);
define('MARGIN_PERCENT', 3);
define('OFFSET_TOP_PERCENT', 10);
define('AVATAR_WIDTH_PERCENT', 8);
define('USERNAME_FONTSIZE_PERCENT', 3.7);
define('PIP_HEIGHT_PERCENT', 4);
define('MAX_LOGO_WIDTH_PERCENT', 55);
define('MAX_LOGO_HEIGHT_PERCENT', 14);
define('MAX_LOGO_WIDTH_PERCENT_DEV', 30);
define('MAX_LOGO_HEIGHT_PERCENT_DEV', 14);

Class ImageGenerator {

    private $dev_filename;
    
    function __construct() {
    	$this->dev_filename = "upload/dev.jpg";
    }

    public function generate($filename,$avatar_filename,$username,$date,$hashtag,$location,$event_filename){
		$mask_filename = 'mask.png';
		$fontEng = "prox.otf";
		$font = "ProximaNovaRegular.ttf";
		$fontBold = "ProximaNovaBold.ttf";
		$pip_filename = "pip.png";

		$username = (strlen($username)>20)?(substr($username, 0, 17)."..."):$username;

		$a6width = 100;
		$a6height = 148;

		// ширина и высота холста
		$image_width = intval($a6width*8); // 720
		$image_height = intval($a6height*8); // 864

		$photo_size = intval($image_width*PHOTO_WIDTH_PERCENT/100);

		$margin = $image_width*MARGIN_PERCENT/100;
		$offset_top = intval($image_height*OFFSET_TOP_PERCENT/100);

		$font_size = $image_width*USERNAME_FONTSIZE_PERCENT/100;

		// создаем пустое полотно
		$image_p = imagecreatetruecolor($image_width, $image_height);

		imagefill($image_p, 0, 0, 0xFFFFFF);

		// Рисование аватарки
		$avatar_size = intval($image_width*AVATAR_WIDTH_PERCENT/100);
		$this->setImage($image_p, $this->openImage($avatar_filename), $margin+$margin/3.5, ($offset_top-$avatar_size)/2-$margin/1.6, $avatar_size );

		// Рисование рамки аватарки
		$this->setImage($image_p, $this->openImage($mask_filename), $margin+$margin/3.5, ($offset_top-$avatar_size)/2-$margin/1.6, $avatar_size );

		// Пользователь
		imagettftext($image_p, $font_size, 0, ($avatar_size + $margin*2)+$margin/3.5, (($offset_top-$font_size)/2+$font_size*0.98)-$margin*0.8, 0x12568C, $fontEng, $username);

		// Дата
		$testbox = imagettfbbox($font_size*0.8, 0, $fontBold, $date);
		imagettftext($image_p, $font_size*0.8, 0, $image_width-$margin-$testbox[2]-1+$margin/3.5, ($offset_top-$font_size*0.8)/2+$font_size*0.98*0.8-$margin*0.8, 0xa5a7aa, $fontBold, $date);

		// Рисование фотографии
		$this->setImage($image_p, $this->openImage($filename), ($image_width-$photo_size)/2+$margin/3.5, $offset_top-$margin/2, $photo_size, $photo_size, true );

		// Рисование пипки
		$this->setImage($image_p, $this->openImage($pip_filename), $margin+$margin/3.5, $photo_size + $offset_top + $margin, $image_height*PIP_HEIGHT_PERCENT/100 );

		// Местоположение
		imagettftext($image_p, $font_size*0.9, 0, $margin*2.8+$margin/3.5, ($photo_size + $offset_top + $margin*2.85), 0x12568C, $fontBold, $location);

		// Хэштег
		imagettftext($image_p, $font_size*0.9, 0, $margin+$margin/3.5, ($photo_size + $offset_top + $font_size*0.9 + $margin*4.15), 0x000000, $font, $hashtag);

		// Рисование логотипа эвента
		$dev_width = intval($image_width*MAX_LOGO_WIDTH_PERCENT/100);
		$dev_height = intval($image_height*MAX_LOGO_HEIGHT_PERCENT/100);
		$this->setImage($image_p, $this->openImage($event_filename), -$margin+$margin/3.5, -$margin/1.5, $dev_width, $dev_height );

		// Рисование логотипа разработчика
		$dev_width = intval($image_width*MAX_LOGO_WIDTH_PERCENT_DEV/100);
		$dev_height = intval($image_height*MAX_LOGO_HEIGHT_PERCENT_DEV/100);
		$this->setImage($image_p, $this->openImage($this->dev_filename), $margin+$margin/3.5, -$margin/1.5, $dev_width, $dev_height );

		$out_file = md5(rand()."asd".time());
		// вывод
		imagejpeg($image_p, "upload/temp/".$out_file.'.jpg', 100);

		return "upload/temp/".$out_file.'.jpg';
    }

    private function openImage($file) {
        $extension = strtolower(strrchr($file, '.'));
     
        switch($extension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($file);
                break;
            case '.gif':
                $img = @imagecreatefromgif($file);
                break;
            case '.png':
                $img = @imagecreatefrompng($file);
                break;
            default:
                $img = false;
                break;
        }
        return $img;
    }

    private function setImage($image_to, $image, $left, $top, $max_width, $max_height = NULL, $bool = false){
		$image_width = imagesx($image_to);
		$image_height = imagesy($image_to);

		$max_height = ( $max_height == NULL )?$max_width:$max_height;

		$img_width = imagesx($image);
		$img_height = imagesy($image);

		$ratio_orig = $img_width/$img_height;

		if ($max_width/$max_height > $ratio_orig) {
			$width = $max_height*$ratio_orig;
			$height = $max_height;
		} else {
			$height = $max_width/$ratio_orig;
			$width = $max_width;
		}

		$left = ( $left < 0 )?($image_width-$width+$left):$left;
		$top = ( $top < 0 )?($image_height-$height+$top):$top;

		if( $bool == true ){
			if( $width > $height ){
				$top = $top + ($width-$height)/2;
			}else{
				$left = $left + ($height-$width)/2;
			}
		}

		imagecopyresampled($image_to, $image, $left, $top, 0, 0, $width, $height, $img_width, $img_height);
	}

}



?>