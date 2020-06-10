<?php
/*
Template Name: Post Layout without Sidebar
Template Post Type: post, page, event
*/

get_header(); ?>

	<div id="primary" class="content-area content-with-sidebar">
		<div id="main" class="site-main">
	
		<?php
		while ( have_posts() ) :
			set_query_var( 'is_full_width', true );
			the_post();
			get_template_part( 'template-parts/post/content', get_post_format() );
		endwhile; // End of the loop.
		?>

		</div>
	</div>

<?php
get_footer();
