<?php
define('THEME_TEMPLATE', TRUE);
global $smof_data, $us_shortcodes;
define('IS_BLOG', TRUE);
if ($smof_data['search_layout'] == 'Masonry Grid' OR $smof_data['blog_sidebar_pos'] == 'No Sidebar')
{
	define('IS_FULLWIDTH', TRUE);
}
else
{
	// Disabling Section shortcode
	global $disable_section_shortcode;
	$disable_section_shortcode = TRUE;
}
get_header();

// Disabling Section shortcode
global $disable_section_shortcode;
$disable_section_shortcode = TRUE;

$color_class = '';
if (isset($smof_data['titlebar_color_style']) AND $smof_data['titlebar_color_style'] == 'Primary bg | White text'){
	$color_class = ' color_primary';
}
elseif (isset($smof_data['titlebar_color_style']) AND $smof_data['titlebar_color_style'] == 'Alternate bg | Content text'){
	$color_class = ' color_alternate';
}
elseif (isset($smof_data['titlebar_color_style']) AND $smof_data['titlebar_color_style'] == 'Secondary bg | White text'){
	$color_class = ' color_secondary';
}

$header_container_layout_type = ' size_large';
if (isset($smof_data['header_layout_type']) AND $smof_data['header_layout_type'] == 'Ultra Compact'){
	$header_container_layout_type = ' size_small';
}
elseif (isset($smof_data['header_layout_type']) AND $smof_data['header_layout_type'] == 'Compact'){
	$header_container_layout_type = ' size_medium';
}
elseif (isset($smof_data['header_layout_type']) AND $smof_data['header_layout_type'] == 'Huge'){
	$header_container_layout_type = ' size_huge';
}
?>
	<div class="l-submain for_pagehead<?php echo esc_attr($color_class.$header_container_layout_type); ?>">
		<div class="l-submain-h g-html i-cf">
			<div class="w-pagehead">
				<h1><?php echo __('Search Results for', 'us').' "'.esc_attr($s).'"'; ?></h1>
				<p></p>

			</div>
		</div>
	</div>
<?php
if ($smof_data['search_layout'] == 'Masonry Grid')
{
	?>
	<div class="l-submain">
		<div class="l-submain-h g-html i-cf">
			<div class="l-content">
				<?php get_template_part( 'templates/archive_grid_paginated' ); ?>
			</div>
		</div>
	</div>
<?php
}
else
{
	?>
	<div class="l-submain">
		<div class="l-submain-h g-html i-cf">
			<div class="l-content">
				<?php
				switch ($smof_data['search_layout'])
				{
					case 'Small Image' :
						get_template_part( 'templates/archive_small' );
						break;
					default :
						get_template_part( 'templates/archive_big' );
						break;
				}

				?>
			</div>
			<div class="l-sidebar at_left">
				<?php if ($smof_data['blog_sidebar_pos'] != 'Right') dynamic_sidebar('default_sidebar'); ?>
			</div>

			<div class="l-sidebar at_right">
				<?php if ($smof_data['blog_sidebar_pos'] == 'Right') dynamic_sidebar('default_sidebar'); ?>
			</div>
		</div>
	</div>
<?php
}

get_footer(); ?>
