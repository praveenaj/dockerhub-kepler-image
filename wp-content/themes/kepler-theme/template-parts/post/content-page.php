<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kepler_theme
 */

$pageHeaderImage = get_theme_mod('page_header_setting_image');
if(!$pageHeaderImage){
	$defaultImage = "background-image:url(".get_stylesheet_directory_uri() . "/img/cover_c.jpg".")";
}
?>
<div class="page-header" data-kepler="hero-parrallax" >
	<div class="inner" style="<?php echo esc_html($defaultImage);?>">
		<div class="container">
			<?php if (function_exists('kepler_theme_breadcrumb')) kepler_theme_breadcrumb(); ?><!-- breadcrumb -->
			<?php the_title('<h1 class="page-title">', '</h1>'); ?><!-- .title -->
		</div><!-- .container -->
	</div>
</div><!-- .entry-header -->
<div class="container">
	<div class="page-content-inner">
		<?php
		the_content();
		wp_link_pages(array(
			'before' => '<div class="page-links">' . esc_html__('Pages:', 'kepler_theme'),
			'after'  => '</div>',
		));
		?>
		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if (comments_open()) :
			comments_template();
		endif;
		?>
	</div><!-- .entry-content -->
</div><!-- .container -->


