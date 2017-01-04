<?php
if (us_is_vc_fe()) {
	$default_dir = vc_manager()->getDefaultShortcodesTemplatesDir() . '/';
	if ( is_file( $default_dir . 'vc_accordion.php' ) ) {
		include( $default_dir . 'vc_accordion.php' );
		return;
	}
}
$attributes = shortcode_atts(
	array(
		'toggle' => '',
		'title_center' => '',
		'el_class' => '',
	), $atts);

global $first_tab, $first_tab_title, $auto_open;


$toggle_class = '';
if ($attributes['toggle'] == 'yes' OR $attributes['toggle'] == 1) {
	$toggle_class = ' type_toggle';
} else {
	$auto_open = TRUE;
	$first_tab_title = TRUE;
	$first_tab = TRUE;
}
if ($attributes['el_class'] != '') {
	$attributes['el_class'] = ' '.$attributes['el_class'];
}
$title_center_class = ($attributes['title_center'] == 'yes' OR $attributes['title_center'] == 1)?' title_center':'';

$output = '<div class="w-tabs layout_accordion'.$toggle_class.$title_center_class.$attributes['el_class'].'">'.do_shortcode($content).'</div>';

$auto_open = FALSE;
$first_tab_title = FALSE;
$first_tab = FALSE;

echo $output;