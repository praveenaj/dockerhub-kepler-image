<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Image Shortcode
 */
if (!defined('ABSPATH')) {  // Cannot access pages directly.
    exit('Direct script access denied.');
}

/**
 * Kepler Image Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_image($params, $content = null) {
    extract(shortcode_atts(array(
        'id' => '',
        'preset' => '',
        'url' => '',
        'link_type' => '',
        'link_target' => '',
        'link_opennewtab' => '',
        'link_emailsubject' => '',
        'link_alttext' => '',
        'placeholder' => '',
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
            $link_target = "mailto:" . $link_target . "?subject = " . $link_emailSubject;
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
    $theme_url = get_template_directory_uri();
    $result = '<div class="image-wrapper ' . $preset . ' kp_' . $id . '" ' . $parrallaxString . '>';
    if ($placeholder) {
        $result .= '<img src="' . $theme_url . '/img/img-holder.png">';
    }
    $result .= '<a ' . $data_targert . $data_tab . 'href="' . $link_target . '">';
    if ($placeholder) {
        $result .= '';
    } else {
        $result .= '<img src="' . $url . '" alt="' . $link_alttext . '">';
    }

    $result .= '</a>';
    $result .= '</div>';
    return force_balance_tags($result);
}
add_shortcode('kp_image', 'kp_image');
