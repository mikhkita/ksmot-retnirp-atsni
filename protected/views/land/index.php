<!DOCTYPE html>
<html>
<head>
    <title><?=$this->getParam("TITLE")?></title>
    <meta name="keywords" content='<?=$this->getParam("KEYWORDS")?>'>
    <meta name="description" content='<?=$this->getParam("DESCRIPTION")?>'>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/reset.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/jquery.fancybox.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/KitAnimate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/land-admin.css" type="text/css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/land.css" type="text/css">
    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">

    <meta name="viewport" content="width=1000">

    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.fancybox.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jssor.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jssor.slider.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/TweenMax.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/swipe.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/carousel.lite.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/css3-mediaqueries.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/KitProgress.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/KitAnimate.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/device.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/KitSend.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/land-admin.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/land.js"></script>
    <?php foreach ($this->scripts AS $script): ?><script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/<?php echo $script?>.js"></script><? endforeach; ?>
</head>
<body>
    <?=$this->topMenu();?>
    <ul class="ps-lines">
        <li class="v" style="margin-left:-501px"></li>
        <li class="v" style="margin-left:500px"></li>
        <li class="v" ></li>
    </ul>
    <div class="b b-1">
        <div class="b-video-cont">
           <!--  <video class="b-video" autoplay loop muted id="bgvideo">
                <source src="<?php echo Yii::app()->request->baseUrl;?>/video/5.mp4" type="video/mp4"></source>
            </video> -->
        </div>
        <div class="b-back">
            <div class="b-block">
                <div class="head clearfix">
                    <a href="<?php echo Yii::app()->request->baseUrl;?>">
                        <img src="<?php echo Yii::app()->request->baseUrl;?>/i/b-1/motors.png">
                    </a>
                    <p><?=$this->getText(1)?></p>
                    <div class="clearfix right tel">
                        <h2><?=$this->getText(2)?></h2>
                        <div class="clearfix">
                            <img class="left" src="<?php echo Yii::app()->request->baseUrl;?>/i/b-1/tel.png">
                            <h3 class="right"><?=$this->getText(3)?></h3>
                        </div>
                        <div class="clearfix">
                            <img class="left" src="<?php echo Yii::app()->request->baseUrl;?>/i/b-1/tel.png">
                            <h3 class="right"><?=$this->getText(16)?></h3>
                        </div>
                        <a href="#" class="fancy" data-block="#callback">Заказать звонок</a>
                    </div>
                </div>
                <div class="content">
                    <p><?=$this->getText(4,array("class"=>"inline"))?> <b id="title-name"><?=$images['name']?></b> <?=$this->getText(5,array("class"=>"inline"))?></p>
                    <h2><?=$this->getText(6)?></h2>
                </div>
                <div class="clearfix bot">
                    <div class="car-wrap left">
                        <div class="car">
                            <h2>
                                <?=$this->replaceToSpan($this->getText(15,array("reload"=>true)))?>
                            </h2>
                            <p><?=$this->getText(8)?></p>
                        </div>
                        <img id="car-img" src="<?=$images['car']?>">
                    </div>
                    
                    <div class="clearfix right typecar">
                        <div>
                            <div class="tc">
                                <h2 class="<? if( $images['logo'] == "" ) echo "no-logo"?>"><?=$this->getText(9,array("class"=>"inline"))?> <?if($images['name'] == "автомобиль"):echo "вашего автомобиля"?><?else: echo $images['name']?><?endif;?> <?=$this->getText(10,array("class"=>"inline"))?></h2>
                            </div>
                            <div class="tc <? if( $images['logo'] == '' ) echo 'hidden'?>">
                                <img id="logo-img" src="<?=$images['logo']?>">
                            </div>
                        </div>
                        <form action="<?php echo Yii::app()->request->baseUrl;?>/kitsend.php" method="post" data-block="#b-popup-2">
                            <select name="1" data-brand="<?if( $images['name'] != 'автомобиль' ) echo $images['name']?>" required>
                                <option value="" disabled selected>Марка</option>
                                <?php foreach ($model as $mark): ?>
                                    <option value="<?=$mark->name?>"><?=$mark->name?></option>
                                <? endforeach; ?>
                                <option value="другое">Другое</option>
                            </select>
                            <input type="hidden" name="1-name" value="Марка"/>
                            <select name="2" required>
                                <option value="" disabled selected>Модель</option>
                            </select>
                            <input type="hidden" name="2-name" value="Модель"/>
                            <select name="3" required>
                                <option value="" disabled selected>Двигатель</option>
                            </select>
                            <input type="hidden" name="3-name" value="Двигатель"/>
                            <input type="text" name="phone" placeholder="Введите ваш телефон" required>

                            <input type="hidden" name="subject" value="Заявка на чип-тюнинг"/>
                            <input type="submit" class="b-green-button ajax" value="Рассчитать прирост">
                        </form>
                        <p><?=$this->getText(11)?></p>
                    </div>                  
                </div>
                <div class="sale-cont">
                    <img src="<?php echo Yii::app()->request->baseUrl;?>/i/b-1/triangle.png">
                    <div class="sale clearfix">
                        <img class="left" src="<?php echo Yii::app()->request->baseUrl;?>/i/b-1/key.png">
                        <div class="left">
                            <h2><?=$this->getText(12)?></h2>
                            <h3><?=$this->getText(13)?></h3>
                        </div>
                    </div>
                </div>
            </div>
         </div>
    </div>
    <div class="b b-1-1">
        <div class="clearfix b-block">
            <div>
                <img class="lock left" src="<?php echo Yii::app()->request->baseUrl;?>/i/lock.png">
                <p class="left"><?=$this->getText(14)?></p>
            </div>
            <!-- <img class="arrow left" src="<?php echo Yii::app()->request->baseUrl; ?>/i/b-1/arrow.png"> -->
        </div>
    </div>
    <div style="display:none;">
        <div id="callback">
            <div class="b-popup">
                <div class="clearfix">
                    <h2>Заказать обратный звонок</h2>
                </div>
                <form action="<?php echo Yii::app()->request->baseUrl;?>/kitsend.php" method="post" data-block="#b-popup-2">
                    <input type="text" name="name" placeholder="Введите ваше имя" required>
                    <input type="text" name="phone" placeholder="Введите ваш телефон" required>
                    <input type="hidden" name="subject" value="Обратный звонок"/>
                    <input type="submit" class="b-green-button ajax" value="Заказать звонок">
                </form>
                <!-- <p>Сегодня рассчитали уже <b>132</b> варианта чип-тюнинга</p> -->
            </div>
        </div>
        <div id="b-popup-2">
            <div class="b-thanks b-popup">
                <h2>Спасибо!</h2>
                <p>Ваша заявка успешно отправлена.<br/>Наш менеджер свяжется с Вами в течение часа.</p>
                <input type="submit" class="b-green-button" onclick="$.fancybox.close(); return false;" value="Закрыть">
            </div>
        </div>
        <div id="b-popup-error">
            <div class="b-thanks b-popup">
                <h2>Ошибка отправки!</h2>
                <p>Приносим свои извинения. Пожалуйста, попробуйте отправить Вашу заявку позже.</p>
                <input type="submit" class="b-green-button" onclick="$.fancybox.close(); return false;" value="Закрыть">
            </div>
        </div>
        <?php foreach ($model as $mark): ?>
            <select name="<?=$mark->name?>" data-car="<?if( $mark->car != '' ) echo $mark->car?>" data-logo="<?if( $mark->logo != '' ) echo $mark->logo?>">
                <option value="" disabled selected>Модель</option>
            <?php foreach ($mark->models as $car_model): ?>  
                <option value="<?=$car_model->name?>"><?=$car_model->name?></option>
            <? endforeach; ?>
            <option value="другое">Другое</option>
            </select>
            <img src="<?=$mark->car?>">
            <img src="<?=$mark->logo?>">
        <? endforeach; ?>
        <?php foreach ($model as $mark): ?>
            <?php foreach ($mark->models as $car_model): ?> 
                <select name="<?=$car_model->name?>">
                    <option value="" disabled selected>Двигатель</option>
                    <?php foreach ($car_model->engines as $engine): ?>  
                        <option value="<?=$engine['name']." (".$engine['horsepower'].")"?>"><?=$engine['name']." (".$engine['horsepower'].")"?></option>
                    <? endforeach; ?>
                    <option value="другое">Другое</option>
                </select>
            <? endforeach; ?>
        <? endforeach; ?>
    </div>
</body>
</html>