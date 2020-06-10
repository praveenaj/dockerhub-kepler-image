<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * WP thirdparty shortcode previewer Shortcode
 */
if (!defined('ABSPATH')) {  // Cannot access pages directly.
    exit('Direct script access denied.');
}

/**
 * WP thirdparty shortcode previewer Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_shortcode($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
        'id' => '',
        'shortcode' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
    ), $params));
    $parrallaxString = "";
    if ($parrallax_active == 'true') {
        $parrallaxString = 'data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '"';
    }
    $content = preg_replace('/<br class="nc".\/>/', '', $shortcode);
    $result = '<div class="shortcode ' . $preset . " kp_" . $id . ' ' . $parrallaxString . '">';
    $result .= do_shortcode(urldecode($shortcode));
    $result .= '</div>';
    return force_balance_tags($result);
}
add_shortcode('kp_shortcode', 'kp_shortcode');
