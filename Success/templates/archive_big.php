<?php global $smof_data, $us_thumbnail_size;
if (have_posts()) : ?>
	<div class="w-blog imgpos_attop">
		<div class="w-blog-list">
			<?php
			while (have_posts())
			{
				the_post();
				$us_thumbnail_size = 'blog-large';
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
<?php else : ?>
	<?php _e('No posts were found.', 'us'); ?>
<?php endif; ?>