<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Carousel Slider Shortcode
 */
if (!defined('ABSPATH')) {  // Cannot access pages directly.
    exit('Direct script access denied.');
}

/**
 * Carousel Slider Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_slider($params, $content = null) {
    extract(shortcode_atts(array(
        'class' => '',
        'preset' => '',
        'autoplay' => '',
        'autoplayduration' => '',
        'autoplayonce' => '',
        'responsive' => '',
        'slidesperview' => '',
        'slidewidth' => '',
        'spacebetween' => '',
        'slidesdesktop' => '',
        'slidestablet' => '',
        'slidesmobile' => '',
        'transitionspeed' => '',
        'controls_controlbuttons' => '',
        'controls_pagination' => '',
        'resolution_orginal' => '',
        'animationcontenttransitionspeed' => '',
        'animationcontenttransition' => '',
        //Used for Displaying of the Slider In the Toolbar :: DO NOT REMOVE
        'display_arrow_preview' => '',
        'display_pagination_preview' => ''
    ), $params));
    $autoplay_string = '';
    $autoplayduration_string = '';
    $autoplayonce_string = '';
    $responsive_string = '';
    $slidesperview_string = '';
    $slidewidth_string = '';
    $spacebetween_string = '';
    $slidesdesktop_string = '';
    $slidestablet_string = '';
    $slidesmobile_string = '';
    $transitionspeed_string = '';
    $controls_controlbuttons_string = '';
    $controls_pagination_string = '';
    $resolution_orginal_string = '';
    $animationcontenttransitionspeed_string = '';
    $animationcontenttransition_string = '';

    if ($autoplay) {
        $autoplay_string = ' data-auto-play="' . $autoplay . '" ';
    }
    if ($autoplayduration) {
        $autoplayduration_string = ' data-auto-play-duration="' . $autoplayduration . '" ';
    }
    if ($autoplayonce) {
        $autoplayonce_string = ' data-auto-play-once="' . $autoplayonce . '"';
    }
    if ($responsive) {
        $responsive_string = ' data-responsive="' . $responsive . '" ';
    }
    if ($slidesperview) {
        $slidesperview_string = ' data-slides-per-view="' . $slidesperview . '" ';
    }
    if ($slidewidth) {
        $slidewidth_string = ' data-slide-width="' . $slidewidth . '" ';
    }
    if ($spacebetween) {
        $spacebetween_string = ' data-space-between="' . $spacebetween . '" ';
    }
    if ($slidesdesktop) {
        $slidesdesktop_string = ' data-slides-desktop="' . $slidesdesktop . '" ';
    }
    if ($slidestablet) {
        $slidestablet_string = ' data-slides-tablet="' . $slidestablet . '" ';
    }
    if ($slidesmobile) {
        $slidesmobile_string = ' data-slides-mobile="' . $slidesmobile . '" ';
    }
    if ($transitionspeed) {
        $transitionspeed_string = ' data-transition-speed="' . $transitionspeed . '" ';
    }
    if ($controls_controlbuttons) {
        $controls_controlbuttons_string = ' data-control-buttons="' . $controls_controlbuttons . '" ';
    }
    if ($controls_pagination) {
        $controls_pagination_string = ' data-pagination="' . $controls_pagination . '" ';
    }
    if ($resolution_orginal) {
        $resolution_orginal_string = ' data-resolution_orginal="' . $resolution_orginal . '" ';
    }
    if ($animationcontenttransitionspeed) {
        $animationcontenttransitionspeed_string = ' data-content-transition-speed="' . $animationcontenttransitionspeed . '" ';
    }
    if ($animationcontenttransition) {
        $animationcontenttransition_string = ' data-content-transition="' . $animationcontenttransition . '" ';
    }
    $data_var = $autoplay_string .
        $autoplayduration_string .
        $autoplayonce_string .
        $responsive_string .
        $slidesperview_string .
        $slidewidth_string .
        $spacebetween_string .
        $slidesdesktop_string .
        $slidestablet_string .
        $slidesmobile_string .
        $transitionspeed_string .
        $controls_controlbuttons_string .
        $controls_pagination_string .
        $resolution_orginal_string .
        $animationcontenttransitionspeed_string .
        $animationcontenttransition_string;
    $result = '<div data-init-plugin="swiper" class="swiper-container ' .  $class . ' ' . '"' . $data_var . '><div class="swiper-wrapper">';
    if ($display_arrow_preview) {
        $result .= '<div class="swiper-button-next">' . do_shortcode("[kp_icon iconClass='kp_icon_chevron_right'][/kp_icon]") . '</div><div class="swiper-button-prev">' . do_shortcode("[kp_icon iconClass='kp_icon_chevron_left'][/kp_icon]") . '</div>';
    }
    $result .= do_shortcode($content);
    if ($display_pagination_preview) {
        $result .= '<div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets"><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span></div>';
    }
    $result .= '</div>';

    if ($controls_pagination) {
        $result .= '<div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets"><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span></div>';
    }

    if ($controls_controlbuttons) {
        $result .= '<div class="swiper-button-next">' . do_shortcode("[kp_icon iconClass='kp_icon_chevron_right'][/kp_icon]") . '</div><div class="swiper-button-prev">' . do_shortcode("[kp_icon iconClass='kp_icon_chevron_left'][/kp_icon]") . '</div>';
    }

    $result .= '</div>';
    return force_balance_tags($result);
}
add_shortcode('kp_slider', 'kp_slider');


function kp_slide($params, $content = null) {

    extract(shortcode_atts(array(
        'class' => 'swiper-slide',
        'preset' => '',
        'slidewidth' => '',
        'multilayeredbg' => '',
        'multilayeredbgorder' => '',
        'bgvideotype' => '',
        'bgvideoid' => ''
    ), $params));

    $slidewidth_string = '';

    if ($slidewidth != 'null') {
        $slidewidth_string = ' style="width: ' . $slidewidth . 'px" ';
    }

    $result = '<div ' . $slidewidth_string . ' class="swiper-slide ' . $class . ' ' . $preset . '">';

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

add_shortcode('kp_slide', 'kp_slide');
