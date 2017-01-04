<?php
$attributes = shortcode_atts(array(
	'icon' => "",
	'color' => "",
	'size' => "",
	'with_circle' => false,
	'link' => "",
	'external' => false,
), $atts);

$color_class = ($attributes['color'] != '')?' color_'.$attributes['color']:' color_text';
$size_class = ($attributes['size'] != '')?' size_'.$attributes['size']:'';
$with_circle_class = ($attributes['with_circle'] == 1 OR $attributes['with_circle'] == 'yes')?' type_solid':'';

if ($attributes['link'] != '') {
$link = $attributes['link'];
$link_start = '<a class="w-icon-link" href="'.$link.'"';
$link_start .= ($attributes['external'] == 1 OR $attributes['external'] == 'yes')?' target="_blank"':'';
$link_start .= '>';
$link_end = '</a>';
}
else
{
$link_start = '<span class="w-icon-link">';
			$link_end = '</span>';
}

$output = 	'<span class="w-icon'.$color_class.$size_class.$with_circle_class.'">
						'.$link_start.'
							<i class="fa fa-'.$attributes['icon'].'"></i>
						'.$link_end.'
					</span>';

echo $output;