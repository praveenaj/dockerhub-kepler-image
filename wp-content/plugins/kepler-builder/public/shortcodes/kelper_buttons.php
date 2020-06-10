<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Buttons/Link Element Shortcodes
 */
if (!defined('ABSPATH')) {  // Cannot access pages directly.
    exit('Direct script access denied.');
}

/**
 * Kepler Buttons / Links Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_button($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
        'link_type' => '',
        'link_target' => '',
        'link_opennewtab' => '',
        'link_emailsubject' => '',
        'id' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
    ), $params));
    $data_targert = '';
    $data_tab = '';
    switch (esc_attr($link_type)) {
        case 'urlView':
            $link_target = $link_target;
            break;
        case 'pageView':
            $link_target = $link_target;
            break;
        case 'sectionView':
            $link_target = "#" . $link_target;
            $data_targert = " data-scroll-to='true'";
            break;
        case 'emailView':
            $link_target = "mailto:" . $link_target . "?subject = " . $link_emailsubject;
            break;
        case 'phoneView':
            $link_target = "tel:" . $link_target;
            break;
        default:
            $link_target = "javascript:void(0)";
            break;
    }
    if ($link_opennewtab == 'true') {
        $data_tab = 'target="_blank"';
    }
    $parrallaxString = "";
    if ($parrallax_active == 'true') {
        $parrallaxString = 'data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '"';
    }
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result = '<a class="' . $preset . ' kp_' . $id . '"' . $data_targert . $data_tab . ' href="' . $link_target . '" ' . $parrallaxString . ' >';

    $result .= do_shortcode($content);
    $result .= '</a>';
    return force_balance_tags($result);
}
add_shortcode('kp_button', 'kp_button');
