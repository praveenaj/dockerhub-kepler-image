<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * WP Logo Shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
    exit( 'Direct script access denied.' );
}

/**
 * Kepler Logo Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_logo( $params, $content=null ) {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    $logo_unset = false;
    $logo_unset_class = '';
    if(!$image){
        $logo_unset = true;
        $logo_unset_class = 'placeholder-for-logo';
    }
    extract( shortcode_atts( array(
        'preset' => '',
        'id' => ''
    ), $params ) );
    $content = preg_replace( '/<br class="nc".\/>/', '', $content );
    $result = '<div class="logo-wrapper ' . $preset .' '.$logo_unset_class .' kp_' . $id . '">';
    if(!$logo_unset){
        $result .= '<a href="'.get_home_url().'"><img alt="'.get_bloginfo( 'name' ).'" src="'.$image[0].'"/></a>';
    }
    $result .= '</div>';
    return force_balance_tags( $result );
}
add_shortcode('kp_logo', 'kp_logo');
