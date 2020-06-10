<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kepler_theme
 */

 // Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

get_header(); 
$isKeplerActive = get_option('envato_purchase_code_kepler') !== false;
$isStylekitSet = get_option('kepler_current_style_kit') !== false;
$is_page_compatible_with_kepler = $isKeplerActive && $isStylekitSet;
$entry_class = "";
if(!$is_page_compatible_with_kepler) {
	$entry_class = 'entry-content';
}
?>
  <div id="kepler-content-area"  class="content kp_ <?php echo esc_html($entry_class); ?>">
  <!-- .wrapper-content -->
	  <?php
		if (have_posts()) :
		   while (have_posts()) :
				the_post();
				//Wrap in container if Kepler builder is not setup or if content does not have a section
				if(!$is_page_compatible_with_kepler){
					get_template_part( 'template-parts/post/content-page');
				}else{
					the_content();
				}
		   endwhile;
		endif;
	  ?>
	</div>
<?php
get_footer();
