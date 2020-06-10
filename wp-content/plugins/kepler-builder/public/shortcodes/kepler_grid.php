<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Grid Elements Shortcodes
 */
if (!defined('ABSPATH')) {  // Cannot access pages directly.
    exit('Direct script access denied.');
}

/**
 * Generate nested shortcodes
 *
 * @access public
 * @since 1.0.0
 * @param string    $element    element name
 * @return string
 */
function kp_generateNestedShortcodes($element) {
    for ($i = 0; $i < 10; $i++) {
        $suffix = $i > 0 ? '_' . $i : '';
        add_shortcode('kp_' . $element . $suffix, 'kp_' . $element);
    }
}

/**
 * Kepler Row Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_row($params, $content = null) {
    extract(shortcode_atts(array(
        'class' => '',
        'id' => null,
        'parrallax_active' => false,
        "parrallax_depth" => "0",
    ), $params));
    $parrallaxString = "";
    if ($parrallax_active == 'true') {
        $parrallaxString = 'data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '"';
    }
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result = '<div class="row ' . $class . '" ' . $parrallaxString . ' >';
    $result .= do_shortcode($content);
    $result .= '</div>';
    return force_balance_tags($result);
}
kp_generateNestedShortcodes('row');


/**
 * Kepler Column Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_column($params, $content = null) {
    extract(shortcode_atts(array(
        'class' => '',
        'columnwidthclass' => '',
        'columnoffset' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
    ), $params));
    $parrallaxString = "";
    if ($parrallax_active == 'true') {
        $parrallaxString = 'data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '"';
    }
    $result = '<div class="column ' . $class . ' ' . $columnwidthclass . ' ' . $columnoffset . '" ' . $parrallaxString . '>';
    $result .= do_shortcode($content);
    $result .= '</div>';
    return force_balance_tags($result);
}
kp_generateNestedShortcodes('column');

/**
 * Kepler Container Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_container($params, $content = null) {
    extract(shortcode_atts(array(
        'class' => '',
        'preset' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
        'fluidcontainer' => false,
        'multilayeredbg' => '',
        'multilayeredbgorder' => '',
        'bgvideotype' => '',
        'bgvideoid' => '',
    ), $params));
    if ($fluidcontainer == "stretched") {
        $mainContainerClass = 'container-fluid';
    } else {
        $mainContainerClass = 'container';
    }
    $result = '<div class="' . $mainContainerClass . ' ' . $class . ' ' . $preset . '" data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '">';
    if ($multilayeredbg) {
        $multilayeredbgorder = explode(' ', $multilayeredbgorder);
        foreach ($multilayeredbgorder as $key => $val) {
            $bgContent = '';
            if ($val == 'bgVideo' && ($bgvideotype == 'youtube' || $bgvideotype == 'vimeo') && $bgvideoid != '') {
                $bgContent = do_shortcode('[kp_video videoID="' . $bgvideoid . '" videoType="' . $bgvideotype . '" controls_mute="true" controls_loop="true" controls_info="false" controls_autoplay="true"][/kp_video]');
            }
            $result .= '<div class="' . $val . ' multiLayered">' . $bgContent . '</div>';
        }
    }
    $result .= do_shortcode($content);
    $result .= '</div>';
    return force_balance_tags($result);
}
kp_generateNestedShortcodes('container');

/**
 * Kepler Section Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_section($params, $content = null) {
    extract(shortcode_atts(array(
        'class' => '',
        'helpers' => '',
        'screenHeight' => '',
        'fluid' => false,
        'id' => null,
        'multilayeredbg' => '',
        'multilayeredbgorder' => '',
        'bgvideotype' => '',
        'bgvideoid' => '',
        'clipcontent' => true
    ), $params));

    //Remove BG Image and Position
    $clipContentClass = "";
    if ($clipcontent == "true") {
        $clipContentClass = "clip-content";
    }
    $result = '<section id="' . $id . '" class="section ' . $clipContentClass . ' ' . $screenHeight . ' ' . $class . ' ' . $helpers . '" >';
    if ($multilayeredbg) {
        $multilayeredbgorder = explode(' ', $multilayeredbgorder);
        foreach ($multilayeredbgorder as $key => $val) {
            $bgContent = '';
            if ($val == 'bgVideo' && ($bgvideotype == 'youtube' || $bgvideotype == 'vimeo') && $bgvideoid != '') {
                $bgContent = do_shortcode('[kp_video videoID="' . $bgvideoid . '" videoType="' . $bgvideotype . '" controls_mute="true" controls_loop="true" controls_info="false" controls_autoplay="true"][/kp_video]');
            }
            $result .= '<div class="' . $val . ' multiLayered">' . $bgContent . '</div>';
        }
    }
    $result .= do_shortcode($content);
    $result .= '</section>';
    return force_balance_tags($result);
}
kp_generateNestedShortcodes('section');


/**
 * Kepler Inline Block Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_inline($params, $content = null) {
    extract(shortcode_atts(array(
        'class' => '',
        'id' => null,
        'multilayeredbg' => '',
        'multilayeredbgorder' => ''
    ), $params));

    //Remove BG Image and Position
    $result = '<div class="inline-row ' . $class . '" >';
    if ($multilayeredbg) {
        $multilayeredbgorder = explode(' ', $multilayeredbgorder);
        foreach ($multilayeredbgorder as $key => $val) {
            $result .= '<div class="' . $val . ' multiLayered"></div>';
        }
    }
    $result .= do_shortcode($content);
    $result .= '</div>';
    return force_balance_tags($result);
}
kp_generateNestedShortcodes('inline');

/**
 * Kepler Box Element Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_box($params, $content = null) {
    extract(shortcode_atts(array(
        'class' => '',
        'id' => null,
        'multilayeredbg' => '',
        'multilayeredbgorder' => '',
        'bgvideotype' => '',
        'bgvideoid' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
    ), $params));
    $parrallaxString = "";
    if ($parrallax_active == 'true') {
        $parrallaxString = 'data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '"';
    }
    //Remove BG Image and Position
    $result = '<div class="box ' . $class . '" ' . $parrallaxString . '>';
    if ($multilayeredbg) {
        $multilayeredbgorder = explode(' ', $multilayeredbgorder);
        foreach ($multilayeredbgorder as $key => $val) {
            $bgContent = '';
            if ($val == 'bgVideo' && ($bgvideotype == 'youtube' || $bgvideotype == 'vimeo') && $bgvideoid != '') {
                $bgContent = do_shortcode('[kp_video videoID="' . $bgvideoid . '" videoType="' . $bgvideotype . '" controls_mute="true" controls_loop="true" controls_info="false" controls_autoplay="true"][/kp_video]');
            }
            $result .= '<div class="' . $val . ' multiLayered">' . $bgContent . '</div>';
        }
    }
    $result .= do_shortcode($content);
    $result .= '</div>';
    return force_balance_tags($result);
}
kp_generateNestedShortcodes('box');

/**
 *  Line Break Shortcode
 *
 * @access public
 * @since 1.0.0
 * @return string
 */
function kp_line_break_shortcode() {
    return '<br />';
}
add_shortcode('br', 'kp_line_break_shortcode');
