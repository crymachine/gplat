<?php
class campaigns_controller {
	public static function index() { 
		include( gplat_dir_path . 'views/campaigns/index.php' ); 
	}
	public static function dem_detail() { 
		include( gplat_dir_path . 'views/campaigns/dem-detail.php' ); 
	}
	public static function sms_detail() { 
		include( gplat_dir_path . 'views/campaigns/sms-detail.php' ); 
	}
}
