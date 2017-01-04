<?php
// We'll need the isotope script for this, but only once
if ( ! wp_script_is('us-isotope', 'enqueued')){
	wp_enqueue_script('us-isotope');
}
// We need fotorama script for this, but only once per page
if ( ! wp_script_is('us-fotorama', 'enqueued')){
	wp_enqueue_script('us-fotorama');
}
global $smof_data, $us_thumbnail_size;
?><div class="w-blog type_masonry imgpos_attop">
	<div class="w-blog-list">

		<?php
		while (have_posts())
		{
			the_post();
			$us_thumbnail_size = 'blog-grid';
			get_template_part('templates/blog_single_post');
		}
		?>

	</div>
</div>
<div class="w-blog-pagination">
	<?php
	the_posts_pagination( array(
		'prev_text' => '<',
		'next_text' => '>',
		'before_page_number' => '<span>',
		'after_page_number' => '</span>',
	) );
	?>
</div>
