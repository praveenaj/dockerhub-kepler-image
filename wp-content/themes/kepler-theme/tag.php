<?php
/**
 * The template for displaying Tag pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kepler_theme
 */

 // Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="main" class="site-main" role="main">
		<?php
		if ( have_posts() ) : ?>
            <div class="container main-container">
                <div class="article-wrapper">
                    <div class="article-inner full-width no-margin">
                        <header class="post-filter-wrapper">
                           <h4><?php esc_html_e('Tags : ','kepler_theme'); ?> <?php single_cat_title(); ?></h4>
                        </header>
                        <div class="row">
                            
                            <?php
                            /* Start the Loop */
                            while ( have_posts() ) : the_post(); ?>
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
                            endwhile;
                            ?>
                        </div>
                        <?php 
                            $iconPrev = kepler_theme_get_icon('kp_icon_chevron_left');
                            $iconNext = kepler_theme_get_icon('kp_icon_chevron_right');
    
                            the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => $iconPrev,
                            'next_text' => $iconNext
                            ));
                        else :
                            get_template_part( 'template-parts/post/content', 'none' );

                        endif; ?>
            
                    </div>
                </div>
            </div>
            <?php
            get_footer();?>
		</div><!-- #main -->
	</div><!-- #primary -->


