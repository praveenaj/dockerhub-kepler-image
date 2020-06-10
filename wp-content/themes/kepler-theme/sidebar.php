<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kepler_theme
 */

 // Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

if ( ! is_active_sidebar( 'main-sidebar' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area" role="complementary">
	<?php 
	kepler_theme_author_info(true);?>
	<?php
	dynamic_sidebar( 'main-sidebar' ); ?>
</aside>
