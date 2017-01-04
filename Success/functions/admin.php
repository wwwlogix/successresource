<?php

function us_enqueue_editor_style() {
	add_editor_style( 'functions/tinymce/mce_styles.css' );
}

add_action('init', 'us_enqueue_editor_style');

// Switch off Otimized JS option for Ultimate Addons, so its JS will load after ours
function us_ultimate_addons_for_vc_integration()
{
	if(get_option('ultimate_js')) {
		if(get_option('ultimate_js') === '' OR get_option('ultimate_js') === 'enable')
			update_option('ultimate_js', 'disable');
	}
	else {
		update_option( 'ultimate_js', 'disable' );
	}

	update_option( 'ultimate_updater', 'disabled' );

	if (class_exists('GlobalsRevSlider')) {

		$version = GlobalsRevSlider::SLIDER_REVISION;

		if(version_compare($version, '5.0', '<')){
			global $wpdb;

			$settings = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."revslider_settings", ARRAY_A);//print_r($settings);

			$set = array();
			if($settings !== false){
				$set = unserialize($settings['general']);//print_r($set);
				if ($set['js_to_footer'] != 'on') {
					$set['js_to_footer'] = 'on';
					$settings['general'] = serialize($set);
					$id = $settings['id'];
					unset($settings['id']);//print_r($settings);
					$update = $wpdb->update($wpdb->prefix."revslider_settings", $settings, array('id' => $id));
				}

			}else{
				$set['js_to_footer'] = 'on';
				$set = serialize($set);
				$insert = $wpdb->insert($wpdb->prefix."revslider_settings",
					array('general' => $set));
			}
		}else{
//			$operations = new RevSliderOperations();
//			$arrValues = $operations->getGeneralSettingsValues();
//			$arrValues['js_to_footer'] = 'on';
//			$operations->updateGeneralSettings($arrValues);
		}
	}
}

// Redirect to Demo Import page after Theme activation
function us_theme_activation()
{
	global $pagenow;
	if (is_admin() && $pagenow == 'themes.php' && isset($_GET['activated'])) {
		//Set menu
		$user = wp_get_current_user();
		update_user_option( $user->ID, 'Success_cpt_in_menu_set', false, true );

		//Redirect to demo import
		header( 'Location: '.admin_url().'themes.php?page=us_demo_import' ) ;
	}
}

add_action('admin_init','us_theme_activation');
add_action('admin_init','us_ultimate_addons_for_vc_integration');

function us_include_cpt_to_menu() {
	global $pagenow;
	if ( is_admin() AND $pagenow == 'nav-menus.php' ) {
		$already_set = get_user_option( 'Success_cpt_in_menu_set' );

		if ( ! $already_set ) {
			$hidden_meta_boxes = get_user_option( 'metaboxhidden_nav-menus' );

			if ($hidden_meta_boxes !== false) {
				if (($key = array_search('add-us_portfolio', $hidden_meta_boxes)) !== false) {
					unset($hidden_meta_boxes[$key]);
				}
				if (($key = array_search('add-us_portfolio_category', $hidden_meta_boxes)) === false) {
					$hidden_meta_boxes[] = 'add-us_portfolio_category';
				}
				if (($key = array_search('add-us_client', $hidden_meta_boxes)) === false) {
					$hidden_meta_boxes[] = 'add-us_client';
				}

				$user = wp_get_current_user();
				update_user_option( $user->ID, 'metaboxhidden_nav-menus', $hidden_meta_boxes, true );
				update_user_option( $user->ID, 'Success_cpt_in_menu_set', true, true );
			}
		}
	}
}

add_action('admin_head','us_include_cpt_to_menu', 99);

// TinyMCE buttons
function us_enqueue_admin_css() {
	wp_enqueue_style( 'us-admin-styles', get_template_directory_uri() . '/functions/assets/css/us.admin.css' );
	wp_enqueue_style( 'us-composer', get_template_directory_uri() . '/vc_templates/css/us.js_composer.css' );
	wp_enqueue_style( 'us-metabox', get_template_directory_uri() . '/vendor/meta-box/css/us_meta_box_style.css ' );
}

add_action( 'admin_print_scripts', 'us_enqueue_admin_css', 12);

if ( ! function_exists('us_is_vc_fe')) {
	function us_is_vc_fe() {
		if (function_exists('vc_mode') AND in_array(vc_mode(), array('page_editable', 'admin_frontend_editor', 'admin_page'))) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/**
 * Get current Success Theme version
 * @return String Current Success Theme version
 */
function us_get_main_theme_version(){
	$theme = wp_get_theme();
	if (is_child_theme()){
		$theme = wp_get_theme($theme->get('Template'));
	}
	return $theme->get('Version');
}
