<?php

/**
 * Template part for displaying default footer. 
 * For usage notes refer to footer.php 
 * This footer will be hidden if Kepler builder assigns a footer for a page
 * @package kepler_theme
 */

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
?>
<section class="default-footer">
	<div class="container">
		<div class="row">
			<div class="col-xl-3 col-md-12 col-sm-12 logo-container">
				<a href="<?php echo get_home_url(); ?>">
					<?php
					/*
					* Display : 
					* Logo, Blog name and description.
					*/
					if (has_custom_logo()) {
						$custom_logo_id = get_theme_mod('custom_logo');
						$logo = wp_get_attachment_image_src($custom_logo_id, 'full');

						echo '<img src="' . esc_url($logo[0]) . '">';
					} else {
						echo '<h4>' . get_bloginfo('name') . '</h4>';
					}
					?>
				</a>
			</div>
			<div class="col-xl-2 col-md-6 col-sm-6 footer-links">
				<label><?php esc_html_e('Discover','kepler_theme'); ?></label>
				<?php
				/*
				* Display : 
				* Parent Level Categories
				* Limited to 6
				*/ 
				$categories = get_categories( array(
					'orderby' => 'name',
					'parent'  => 0,
					'number' => 6
				) );
				 
				foreach ( $categories as $category ) {
					printf( '<a href="%1$s">%2$s</a>',
						esc_url( get_category_link( $category->term_id ) ),
						esc_html( $category->name )
					);
				}
				?>
			</div>
			<div class="col-xl-2 col-md-6 col-sm-6 footer-links">
				<label><?php esc_html_e('Connect','kepler_theme'); ?></label>
				<?php
					wp_nav_menu(array(
						'theme_location' => 'footer-social-menu',
						'menu' => 'Social Menu',
						'fallback_cb' => false
					)); 
				?>
			</div>
			<div class="col-xl-3 col-md-6 col-sm-6 footer-links">
				<label><?php esc_html_e('From The Blog','kepler_theme'); ?></label>
				<?php
					/*
					* Display : 
					* Lastest 3 Posts
					*/
					$args = array( 'numberposts' => '3' );
					$recent_posts = wp_get_recent_posts( $args );
					foreach( $recent_posts as $recent ){
						echo '<a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a>';
					}
					wp_reset_query();
				?>
				<a href="<?php echo home_url(); ?>" class="read-more"><?php esc_html_e('Read More','kepler_theme'); ?></a>
			</div>
			<div class="col-xl-2 col-md-6 col-sm-6 footer-links">
				<label><?php esc_html_e('Legal','kepler_theme'); ?></label>
				<a href="#">Privacy</a>
				<a href="#">Terms</a>
			</div>
		</div>
		<div class="row privacy-policy">
			<div class="col-xl-2 copyright-name">
				<?php echo '<p>	&copy; ' . get_bloginfo('name') . ' ' . date('Y') . '</p>' ?>
			</div>
			<div class="col-xl-10">
				<p><?php esc_html_e("All the information on this website is published in good faith and for general information purpose only. This website does not make any warranties about the completeness, reliability and accuracy of this information. Any action you take upon the information you find on this website, is at your own volition.","kepler_theme"); ?></p>
			</div>
		</div>
	</div>
</section>