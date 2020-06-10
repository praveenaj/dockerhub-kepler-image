<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Placeholder Elements Shortcode
 */
if (!defined('ABSPATH')) {  // Cannot access pages directly.
    exit('Direct script access denied.');
}

/**
 * Dummy Place holder for builder elements
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_placeholder($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
        'templateId' => ''
    ), $params));
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result = '<div class="' . $preset . '" >';
    $result .= do_shortcode($content);
    $result .= '</div>';
    return force_balance_tags($result);
}
add_shortcode('kp_placeholder', 'kp_placeholder');

/**
 * Dummy Map  placeholder for builder elements
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_map_placeholder($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
        'templateId' => '',
        'features' => ''
    ), $params));
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result = '<div class="map-placeholder ' . $preset . '">';
    $result .= '</div>';
    return force_balance_tags($result);
}
add_shortcode('kp_map_placeholder', 'kp_map_placeholder');

/**
 * Kepler generic element display HTML safe tags
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_generic($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
    ), $params));
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result = '<div class="' . $preset . '">';
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result .= do_shortcode($content);
    $result .= '</div>';
    return force_balance_tags($result);
}
add_shortcode('kp_generic', 'kp_generic');
