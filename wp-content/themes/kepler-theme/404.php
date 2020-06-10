<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package kepler_theme
 */
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}

get_header(); ?>

<div id="primary" class="content-area">
	<div id="main" class="site-main container no-padding" role="main">
		<section class="error-404 not-found">
			<div class="col-lg-6 center-ills">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 215 256">
					<defs>
						<style>
						.cls-1{fill:none}.cls-1,.cls-2,.cls-3{stroke:#000;stroke-width:4px}.cls-1,.cls-2{stroke-miterlimit:10}.cls-2,.cls-3{fill:#fff}.cls-3{stroke-linejoin:round}
						</style>
					</defs>
					<path d="M2 2h144l56 56v92h-29v29h-30v30h-30v28H2V2z" class="cls-1"/>
					<path d="M146 2v56h56" class="cls-1"/>
					<rect width="48" height="48" x="96" y="111" rx="6" ry="6"/>
					<rect width="48" height="48" x="102" y="105" class="cls-2" rx="6" ry="6"/>
					<circle cx="65" cy="63" r="29"/>
					<circle cx="71" cy="59" r="29" class="cls-2"/>
					<path d="M75 203l9-8-55-88-7 7v89h53z"/>
					<path d="M30 109v86h52l-52-86z" class="cls-3"/>
					<path d="M213 226c0 16-13 28-29 28h-38v-21h23v-22h22v-22h22z" class="cls-1"/>
				</svg>
			</div>
			<div class="col-lg-6 center-ills">
				<div class="center-text">
					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e('Congratulations! You are one of the elite few who has managed to find our 404 page.','kepler_theme');?></h1>
					</header>

					<div class="page-content">
						<p><?php esc_html_e("Don't worry - this is not a dead end. Just use the navigation at the top to see more options.","kepler_theme");?></p>
					</div>

					<div class="nav-back-wrapper">
						<?php echo kepler_theme_get_icon("kp_icon_chevron_right"); ?>
						<a href="<?php echo get_site_url(); ?>" class="back-to-home"><?php esc_html_e('Take me to the Homepage','kepler_theme');?></a>
					</div>
				</div>
			</div>
		</section>

	</div>
</div>

<?php
get_footer();
