<?php
/**
 * Plugin Name: gplat
 * Plugin URI: http://gplat.wikiadv.com
 * Description: Type here some words to describe the platform...
 * Version: 1.0.0
 * Author: d.m. de rossi
 * Author URI: http://gplat.wikiadv.com
 * License: GPL2
 * Text Domain: gplat
 * Domain Path: /languages/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once plugin_dir_path( __FILE__ ) . 'commons.php';
require_once plugin_dir_path( __FILE__ ) . 'hexerrs-definitions.php';
require_once plugin_dir_path( __FILE__ ) . 'action-result.php';

require_once( plugin_dir_path( __FILE__ ) . 'libs/redbeanPHP/rb-mysql.php' );

R::setup( "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD );
//R::freeze( true );
//R::fancyDebug( true );
R::ext('xdispense', function( $type ){ 
	return R::getRedBean()->dispense( $type ); 
});

define( 'gplat_dir_path', plugin_dir_path( __FILE__ ));
define( 'gplat_dir_url', plugin_dir_url( __FILE__ ));
define( 'gplat_dal_rigenerate', true);
define( 'gplat_dal_table_prefix', 'gplat_');
define( 'gplat_dal_strip_table_prefix', true);
define( 'gplat_require_models_file_path', gplat_dir_path . 'require-models.php');

require_once plugin_dir_path( __FILE__ ) . 'plugin-activator.php';

add_action('login_head', array('gplat', 'custom_loginlogo'));


register_activation_hook( __FILE__ , array( 'plugin_activator', 'activate' ) );

class gplat {
	public static function initialize() {
		add_action('admin_menu', array('gplat', 'gplat_admin_menu'));
		add_action('admin_enqueue_scripts', array('gplat', 'gplat_add_assets'));
		add_action('plugins_loaded', array('gplat', 'create_models_controllers'));
 	}
	public static function create_models_controllers() {
		if ( class_exists('gplat_commons') ) {
			if(!gplat_commons::dir_is_empty(plugin_dir_path( __FILE__ ) . 'models')){
				foreach (glob(plugin_dir_path( __FILE__ ) . 'models/*.php') as $filename) {
					require_once( $filename );
				}	
			}
			if(!gplat_commons::dir_is_empty(plugin_dir_path( __FILE__ ) . 'controllers')){
				foreach (glob(plugin_dir_path( __FILE__ ) . 'controllers/*.php') as $filename) {
					require_once( $filename );
				}
			}
		}
	}
	public static function custom_loginlogo() {
		echo '<style type="text/css">h1 a {background-image: url(\'' . gplat_dir_url . 'images/logo_symbol_256x256.png\') !important; }</style>';
	}	
	public static function gplat_admin_menu() {
		global $parent_file, $submenu_file, $current_screen;
		
		$root_slug = 'gplat-admin';
		$user_data = get_userdata(wp_get_current_user()->id);
		$display_name = strlen($user_data->last_name) + strlen($user_data->first_name) > 0 ? $user_data->last_name . ', ' . $user_data->first_name : $user_data->display_name;
		$display_role = count($user_data->wp_capabilities);
		add_menu_page('gplat 1', '<img src=\'' . gplat_dir_url . 'images/person.png' . '\' style=\'width:78px;height:auto;margin-left:5px;\' class=\'rounded\' title=\'google\'/><div class=\'text-center mt-1\'>' . $display_name . '<br/>(' . $display_role . ')</div>', 'read', 'gplat-admin-user', array('gplat', 'gplat_menu_root'), gplat_dir_url . 'images/icon_.png', 1);

		add_menu_page('gplat', 'gplat', 'read', $root_slug, array('gplat', 'gplat_menu_root'), gplat_dir_url . 'images/icon.png');
		add_submenu_page( $root_slug, 'Dashboard', 'Dashboard', 'read', $root_slug . '-dashboard', array('gplat', 'gplat_menu_root') );

		//add_submenu_page( $root_slug, __('Agencies', gplat_textdomain), __('Agencies', gplat_textdomain), 'read', $root_slug . '-agencies', array('agency_controller', 'index') );
		//add_submenu_page( '', __('Agency Detail', gplat_textdomain), __('Agency Detail', gplat_textdomain), 'read', $root_slug . '-agency-detail', array('agency_controller', 'detail') );

		remove_submenu_page( $root_slug, $root_slug );

		if($_GET['page'] == 'gplat-admin-property-group-detail') {
			$parent_file = 'gplat-admin';
			$submenu_file = 'gplat-admin-properties-groups';
		}

		$screen = get_current_screen();
		$screenname = $screen->base;
		$screentax = $screen->taxonomy;
		$posttype = $screen->post_type;
	}
	public static function gplat_menu_root() { 
		include(gplat_dir_path . 'views/_gplat-view.php');
	}
	public static function gplat_add_assets() {
		wp_enqueue_script("jquery");

		wp_register_script('gplat_popper_js', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js', array ('jquery'), true);
		wp_enqueue_script('gplat_popper_js');

		wp_register_script('gplat_bootstrap_js', '//stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js', array ('jquery'), true);
		wp_enqueue_script('gplat_bootstrap_js');

		wp_register_script('gplat_datatables_js', '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', array ('jquery'), true);
		wp_enqueue_script('gplat_datatables_js');

		wp_register_script('gplat_datatables_bootstrap4_js', '//cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js', array ('gplat_datatables_js'));
		wp_enqueue_script('gplat_datatables_bootstrap4_js');

		wp_register_script('gplat_datatables_google_chart_js', '//www.gstatic.com/charts/loader.js');
		wp_enqueue_script('gplat_datatables_google_chart_js');

		wp_register_script('gplat_datatables_raphael_js', '//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js', array ('jquery'), true);
		wp_enqueue_script('gplat_datatables_raphael_js');

		wp_register_script('gplat_datatables_morris_js', '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js', array ('jquery'), true);
		wp_enqueue_script('gplat_datatables_morris_js');


		wp_register_style('gplat_bootstrap_css', '//stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css');
		wp_enqueue_style('gplat_bootstrap_css');

		wp_register_style('gplat_fontawesome', gplat_dir_url . 'styles/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css', array ('gplat_bootstrap_css'));
		wp_enqueue_style('gplat_fontawesome');

		wp_register_style('gplat_datatables_css', '//cdn.datatables.net/v/bs4/dt-1.10.16/af-2.2.2/fh-3.1.3/r-2.2.1/rg-1.0.2/sc-1.4.4/sl-1.2.5/datatables.min.css', array ('gplat_bootstrap_css'));
		wp_enqueue_style('gplat_datatables_css');

		wp_register_style('gplat_datatables_bootstrap4_css', '//cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css', array ('gplat_datatables_css'));
		wp_enqueue_style('gplat_datatables_bootstrap4_css');	

		wp_register_style('gplat_datatables_morris_css', '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css');
		wp_enqueue_style('gplat_datatables_morris_css');
	}
}
gplat::initialize();
register_deactivation_hook( __FILE__, array( 'plugin_activator', 'deactivate' ) );