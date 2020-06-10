<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Video Shortcode
 */
if (!defined('ABSPATH')) {  // Cannot access pages directly.
    exit('Direct script access denied.');
}

/**
 * Video Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_video($params, $content = null) {
    extract(shortcode_atts(array(
        'preset' => '',
        'id' => '',
        'videoid' => '',
        'videotype' => '',
        'controls_mute' => '',
        'controls_loop' => '',
        'controls_info' => '',
        'controls_autoplay' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
    ), $params));
    $parrallaxString = "";
    if ($parrallax_active == 'true') {
        $parrallaxString = 'data-kepler-parallax="' . $parrallax_active . '" data-depth="' . $parrallax_depth . '"';
    }
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result = '<div data-init-plugin="video" ' . ' class="video-wrapper ' . $preset . ' kp_' . $id . '" data-videoid="' . $videoid . '" data-video-type="' . $videotype . '" data-controls-mute="' . $controls_mute . '" data-controls-loop="' . $controls_loop . '" data-controls-info="' . $controls_info . '" data-controls-autoplay="' . $controls_autoplay . '" ' . $parrallaxString . '><div></div></div>';
    return force_balance_tags($result);
}
add_shortcode('kp_video', 'kp_video');
