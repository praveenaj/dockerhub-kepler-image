<?php
/**
 * Template part for displaying a message when post cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kepler_theme
 */

?>

<section class="no-results not-found container no-padding">
	<?php if(is_search()) : ?>

	<p class="titleSearch">
		<?php esc_html_e('Your search','kepler_theme'); ?> - <?php echo get_search_query(); ?> <?php esc_html__('did not match any Posts','kepler_theme'); ?>
	</p>
	<br>
	<p class="desc-search">
		<?php esc_html_e("Sorry, we couldn't find any results for this search. Maybe give one of these a try?:","kepler_theme"); ?>
	</p>
	<p>
		<?php esc_html_e('Make sure that all words are spelled correctly:','kepler_theme'); ?><br/>
		<?php esc_html_e('Try different keywords:','kepler_theme'); ?><br/>
		<?php esc_html_e('Try more general keywords:','kepler_theme'); ?>
	</p>
	<br>

	<a href="<?php echo get_home_url() ?>">
		<?php echo kepler_theme_get_icon('kp_icon_arrow_left'); ?>
		<?php esc_html_e('No. Take me back to homepage','kepler_theme'); ?>
	</a>
	<?php else : ?>
			<div class="error-404 not-found">
				<div class="col-lg-6 center-ills">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 82 78" width="215">
						<path fill="#BEC0C2" d="M19.8 30.5l-1.8-6c-.4-1.4-1.6-2.6-3-3l-6-1.8c-.7-.2-.7-1.2 0-1.4l6.1-1.8c1.4-.4 2.6-1.6 3-3l1.8-6c.2-.7 1.2-.7 1.4 0l1.8 6.1c.4 1.4 1.6 2.6 3 3l6 1.8c.7.2.7 1.2 0 1.4l-6.1 1.7c-1.4.4-2.6 1.6-3 3l-1.8 6c-.2.7-1.2.7-1.4 0zm13.3 41.1l.7-2.5c.3-.9.9-1.6 1.8-1.8l2.5-.7c.6-.2.6-1 0-1.2l-2.5-.7c-.9-.3-1.6-.9-1.8-1.8l-.7-2.5c-.2-.6-1-.6-1.2 0l-.7 2.5c-.3.9-.9 1.6-1.8 1.8l-2.5.7c-.6.2-.6 1 0 1.2l2.5.7c.9.3 1.6.9 1.8 1.8l.7 2.5c.2.6 1 .6 1.2 0zm34.1-32.8l.9-3c.3-1.1 1.1-1.9 2.2-2.2l3-.9c.7-.2.7-1.3 0-1.5l-3-.9c-1.1-.3-1.9-1.1-2.2-2.2l-.9-3c-.2-.7-1.3-.7-1.5 0l-.9 3c-.3 1.1-1.1 1.9-2.2 2.2l-3 .9c-.7.2-.7 1.3 0 1.5l3 .9c1.1.3 1.9 1.1 2.2 2.2l.9 3c.3.7 1.3.7 1.5 0zm-8.2 20.2l-1-1h-3l-1 1 1 1h3l1-1zm7 0l-1-1h-3l-1 1 1 1h3l1-1zm-5-2v-3l-1-1-1 1v3l1 1 1-1zm0 7v-3l-1-1-1 1v3l1 1 1-1zm-15-52l-1-1h-3l-1 1 1 1h3l1-1zm7 0l-1-1h-3l-1 1 1 1h3l1-1zm-5-2v-3l-1-1-1 1v3l1 1 1-1zm0 7v-3l-1-1-1 1v3l1 1 1-1zm-36 29l-1-1h-3l-1 1 1 1h3l1-1zm7 0l-1-1h-3l-1 1 1 1h3l1-1zm-5-2v-3l-1-1-1 1v3l1 1 1-1zm0 7v-3l-1-1-1 1v3l1 1 1-1z"></path>
					</svg>
				</div>
				<div class="col-lg-6 center-ills">
					<div class="center-text">
						<header class="page-header">
							<h1 class="page-title"><?php esc_html_e('Seems a little quiet over here', 'kepler_theme'); ?></h1>
						</header>

						<div class="page-content">
							<p><?php esc_html_e("Don't worry - this is not a dead end. Just use the navigation at the top to see more options.","kepler_theme");?></p>
						</div>

						<div class="nav-back-wrapper">
							<?php echo kepler_theme_get_icon('kp_icon_arrow_left'); ?>
							<a href="<?php echo get_site_url(); ?>" class="back-to-home"><?php esc_html_e('Take me to the Homepage','kepler_theme');?></a>
						</div>
					</div>
				</div>
		</div>
	<?php endif; ?>
</section><!-- .no-results -->