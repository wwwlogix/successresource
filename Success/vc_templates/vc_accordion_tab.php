<?php
if (us_is_vc_fe()) {
	$default_dir = vc_manager()->getDefaultShortcodesTemplatesDir() . '/';
	if ( is_file( $default_dir . 'vc_accordion_tab.php' ) ) {
		include( $default_dir . 'vc_accordion_tab.php' );
		return;
	}
}
$attributes = shortcode_atts(
	array(
		'title' => '',
		'active' => false,
		'icon' => '',
		'bg_color' => '',
		'text_color' => '',
		'no_indents' => '',
	), $atts);

global $first_tab, $auto_open;
if ($auto_open) {
//	$active_class = ($first_tab)?' active':'';
	$first_tab = FALSE;
} else {
	$active_class = ($attributes['active'])?' active':'';
}

$active_class = ($attributes['active'] == 1 OR $attributes['active'] == 'yes')?' active':'';

$icon_class = ($attributes['icon'] != '')?' fa fa-'.$attributes['icon']:'';
$item_icon_class = ($attributes['icon'] != '')?' with_icon':'';
$no_indents_class = ($attributes['no_indents'] == 'yes' OR $attributes['no_indents'] == 1)?' no_indents':'';

$item_style = $item_custom_class = '';

if ($attributes['bg_color'] != '') {
	$item_style .= 'background-color: '.$attributes['bg_color'].';';
}
if ($attributes['text_color'] != '') {
	$item_style .= ' color: '.$attributes['text_color'].';';
}
if ($item_style != '') {
	$item_style = ' style="'.$item_style.'"';
	$item_custom_class = ' color_custom';
}


$output = 	'<div class="w-tabs-section'.$active_class.$item_icon_class.$item_custom_class.$no_indents_class.'"'.$item_style.'>'.
				'<div class="w-tabs-section-header">'.
					'<div class="w-tabs-section-icon'.$icon_class.'"></div>'.
					'<h4 class="w-tabs-section-title">'.$attributes['title'].'</h4>'.
					'<div class="w-tabs-section-control"><i class="fa fa-angle-down"></i></div>'.
				'</div>'.
				'<div class="w-tabs-section-content">'.
					'<div class="w-tabs-section-content-h">'.
						do_shortcode($content).
					'</div>'.
				'</div>'.
			'</div>';

echo $output;
