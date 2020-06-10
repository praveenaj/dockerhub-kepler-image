<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Heading Element Shortcodes
 */
if (!defined('ABSPATH')) {  // Cannot access pages directly.
    exit('Direct script access denied.');
}
/**
 * Kepler Heading Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_heading($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
        'type' => 'h1',
        'id' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
        'field' => null
    ), $params));
    switch (esc_attr($type)) {
        case 'h1':
            $type = "h1";
            break;
        case 'h2':
            $type = "h2";
            break;
        case 'h3':
            $type = "h3";
            break;
        case 'h4':
            $type = "h4";
            break;
        case 'h5':
            $type = "h5";
            break;
    }
    $parrallaxString = "";
    if ($parrallax_active == 'true') {
        $parrallaxString = 'data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '" ';
    }
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result = '<' . esc_attr($type) . ' class="' . $preset . " kp_" . $id . '" ' . $parrallaxString . '>';
    if ($field) {
        $result .= get_field($field);
    } else {
        $result .= do_shortcode($content);
    }
    $result .= '</' . esc_attr($type) . '>';
    return force_balance_tags($result);
}
add_shortcode('kp_heading', 'kp_heading');
