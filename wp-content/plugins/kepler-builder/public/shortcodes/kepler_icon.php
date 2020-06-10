<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Icon Shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
    exit( 'Direct script access denied.' );
}

/**
 * Kepler Icon Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_icon($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
        'link_type' => '',
        'link_target' => '',
        'link_opennewtab' => '',
        'link_emailsubject' => '',
        'id' => '',
        'iconclass' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
        "inherit_color" => ''
    ), $params));

    $data_targert = '';
    $data_tab = '';
    if($link_target != '') {
        switch (esc_attr($link_type)) {
            case 'urlView':
                if (strpos($link_target, 'http') === false) {//check if url has http added to it
                    $link_target='http://'.$link_target;
                }
                else{
                    $link_target = $link_target;
                }
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
    }


    $inheritColorString = "";
    if($inherit_color == 'false'){
        $inheritColorString = "pointer-events: none; user-select: none;";
    }else{
        $inheritColorString = "color: inherit; pointer-events: none; user-select: none;";
    }

    $parrallaxString = "";
    if($parrallax_active == 'true'){
        $parrallaxString = 'data-kepler-parallax="'.$parrallax_active.'" data-depth="'.$parrallax_depth.'"';
    }
    $iconStyle = get_site_option("kepler_style_kit_icons");
    if($iconStyle == '') {
        $iconStyle = 'Eva';
    }
    
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result='';
    if($link_target != '') {
        $result =  '<a ' . $data_targert . $data_tab . ' href="' . $link_target . '" >';
    }
    $result .= '<div class="icon-wrapper ' . $preset . " kp_" . $id . " " . $iconclass . '" '.$parrallaxString.'>';
    $result .= '<div class="multiLayered bgColor"></div>';
    $result .= '<div class="multiLayered bgGradient"></div>';
    $result .= '<svg class="kp_icon_svg" style="'.$inheritColorString.'">';
    $result .= '<use xlink:href="' . get_template_directory_uri() . '/icons/' . $iconStyle . '/sprite.svg#' . $iconclass . '"></use>';
    $result .= '</svg>';
    $result .= '</div>';
    if($link_target != '') {
        $result .= '</a>';
    }
    return force_balance_tags($result);
}
add_shortcode('kp_icon', 'kp_icon');
