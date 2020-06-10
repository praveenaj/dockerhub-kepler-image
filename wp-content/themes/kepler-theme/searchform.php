<?php
/**
 * The template for displaying search form
 *
 * @package kepler_theme
 */

 // Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}
?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url() ); ?>">
    <div class="search-overlay-form">
        <?php echo kepler_theme_get_icon("kp_icon_search"); ?>
        <input class="text form-control search-overlay-input" type="text" value="" name="s" autocomplete="off" id="searchtext" placeholder="<?php esc_attr_e('What are you looking for?', 'kepler_theme');?>"/>
        <input type="submit" class="submit button hidden" name="submit" value="<?php esc_attr__('Search', 'kepler_theme');?>" />
        <button id="search-overlay-close" class="search-overlay-close"><?php echo kepler_theme_get_icon("kp_icon_close") ?></button>
    </div>
</form>
