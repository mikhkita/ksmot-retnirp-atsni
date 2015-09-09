<div class="b b-kit-top">
	<div class="b-kit-top-fixed">
		<div class="b-block clearfix">
	        <ul class="b-kit-top-menu clearfix left">
	        	<?foreach ($this->adminMenu["items"] as $i => $menuItem):?>
	                <li><a href="<?php echo $this->createUrl('/'.$menuItem->code.'/adminindex')?>"><?=$menuItem->name?></a></li>
	            <?endforeach;?>
	        </ul>
	        <div class="b-kit-top-user right">
	            <span class="left"><? echo $this->user->usr_name; ?> (<? echo mb_strtolower($this->getUserRoleRus(),"UTF-8"); ?>)</span> <a href="<?php echo $this->createUrl('site/logout')?>" class="right">Выйти</a>
	        </div>
	        <div class="b-kit-top-edit right">
	        	<span>Режим правки: </span>
	        	<a href="#" class="b-ajax-link b-kit-switcher right">
				    <div class="b-kit-rail">
				        <div class="b-kit-state1">Вкл.</div>
				        <div class="b-kit-slider"></div>
				        <div class="b-kit-state2">Выкл.</div>
				    </div>
				</a>
				<script>checkCookie();</script>
	        </div>
	    </div>
	</div>
</div>