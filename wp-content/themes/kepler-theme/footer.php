<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kepler_theme
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}
?>

	</div><!-- #content -->
	<footer>
		<?php
		// Check if a footer is set for the current page (using Kepler builder), 
      	// if so, load it else fallback to default footer. 
		$footer_content = kepler_theme()->footer->kepler_theme_footer_content();
		if($footer_content != "") 
			echo do_shortcode($footer_content);
		else
			get_template_part( 'template-parts/layout/default_footer' );
		  ?> 
	</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
