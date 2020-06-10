<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Text Elements Shortcode
 */
if (!defined('ABSPATH')) {  // Cannot access pages directly.
    exit('Direct script access denied.');
}

/**
 * Text Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_text($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
        'id' => ''
    ), $params));
    $parrallaxString = "";
    if ($parrallax_active == 'true') {
        $parrallaxString = 'data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '" ';
    }
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result = '<p class="' . $preset . " kp_" . $id . '" ' . $parrallaxString . '>';
    $result .= do_shortcode($content);
    $result .= '</p>';
    return force_balance_tags($result);
}
add_shortcode('kp_text', 'kp_text');

/**
 * Paragraph Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_paragraph($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
        'id' => ''
    ), $params));
    $parrallaxString = "";
    if ($parrallax_active == 'true') {
        $parrallaxString = 'data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '"';
    }
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result = '<p class="' . $preset . " kp_" . $id . '" ' . $parrallaxString . '>';
    $result .= do_shortcode($content);
    $result .= '</p>';
    return force_balance_tags($result);
}
add_shortcode('kp_paragraph', 'kp_paragraph');
