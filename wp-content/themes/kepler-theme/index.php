<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kepler_theme
 */

 // Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

get_header();
?>

	<div id="primary" class="content-area">
		<div id="main" class="site-main">

		<?php
		if ( have_posts() ) :

			/* Start the Loop */
			$count = 0;
			$stickyFound = false;
			/* Page query number */
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			while ( have_posts() ) : the_post(); 
				/*	
					Check for sticky post 
					Display fullwidth card for sticky and flag found $stickyfound
				*/
				if ( is_sticky() && !is_paged() ):
					$stickyFound = true;?>
					<div class="grid-item sticky-wrapper">
						<?php
							/* Sticky Post Layout */
							get_template_part( 'template-parts/grid/sticky-grid', get_post_format() );?>
					</div>
				<?php
					/*	
						No sticky post segment
						Even if sticky post is not found display a fullwidth card
					*/				
					else :
				?>
				<?php 
					/*	
						Display a full width card if only sticky flag is false,
						count is zero and first page of post navigation
					*/				
					if ( !$stickyFound && $count == 0 && $paged == 1):
				?>
					<div class="grid-item sticky-wrapper">
						<?php
							/* Show first post as featured */
							get_template_part( 'template-parts/grid/sticky-grid', get_post_format() );?>
					</div>
				<?php
					else:
						/*	
							Standard card list
							Wrap all cards expect the first one with a container
						*/							
						if ($count == 1 && $paged == 1 || $count == 0 && $paged > 1):
				?>					
						<div class="container main-container">
							<div class="article-wrapper">
								<div class="full-width">
									<div class="row"> 
						<?php
							endif;
						?>						
						<div class="col-lg-6 col-xl-4 grid-item">
							<?php
								
								/*
								* Include the Post-Format-specific template for the content.
								* If you want to override this in a child theme, then include a file
								* called content-___.php (where ___ is the Post Format name) and that will be used instead.
								*/
								get_template_part( 'template-parts/grid/grid', get_post_format() );?>
						</div>
				<?php
					endif;
				?>
			<?php
				endif;
				$count++;
			endwhile;
			?>
			<?php
				/*	Remeber to close the tags:row */							
				if ($count > 1):
			?>	
				</div><!-- .row-->
			<?php endif; ?>
			<?php 
				$iconPrev = kepler_theme_get_icon('kp_icon_chevron_left');
				$iconNext = kepler_theme_get_icon('kp_icon_chevron_right');

				the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => $iconPrev,
				'next_text' => $iconNext
			) );
			else :
				get_template_part( 'template-parts/post/content', 'none' );

			endif; ?>
			<?php
				/*	Remeber to close the tags: full-width and article-wrapper */							
				if ($count > 1):
			?>	
				</div><!-- .full-width -->
			</div><!-- .article-wrapper -->
			<?php endif; ?>
			<?php
				/*	Add extra container */							
				if ($count <= 1):
			?>
				<div class="container">
			<?php endif; ?>	
				<?php
				/*
				* Template partial for mail chimp
				* Use WP Widget area "MailChimp" in your dashboard to add custom form
				*/
				get_template_part( 'template-parts/layout/mailchimp');?>
		</div><!-- .container -->
	</div><!-- #main -->
</div><!-- #primary -->
<?php
get_footer();
