<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package kepler_theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="main" class="site-main">
	
		<?php
		while ( have_posts() ) :
			set_query_var( 'is_full_width', false );
			the_post();
			get_template_part( 'template-parts/post/content', get_post_format() );
		endwhile; // End of the loop.
		?>

		</div>
	</div>

<?php
get_footer();
