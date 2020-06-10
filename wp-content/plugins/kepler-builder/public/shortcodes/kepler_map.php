<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Google Map Shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
    exit( 'Direct script access denied.' );
}

/**
 * Kepler Google Map Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_map($params, $content = null) {
    extract(shortcode_atts(array(
        'id' => '',
        'preset' => '',
        'cordinates_lat' => '',
        'cordinates_lng' => '',
        'address' => '',
        'zoom' => '',
        'maptype' => '',
        'draggable' => '',
        'marker' => '',
        'json' => '',
        'parrallax_active' => false,
        "parrallax_depth" => "0",
    ), $params));
    $cordinates_lat_string = $cordinates_lat_string = $address_string = "";
    $address_string = $zoom_string = $draggable_string = $marker_string = "";
    $maptype_string = $json_string = $parrallaxString = "";
    if($cordinates_lat){
        $cordinates_lat_string = ' data-cordinates-lat='.$cordinates_lat;
    }
    
    if($cordinates_lng){
        $cordinates_lng_string = ' data-cordinates-lng='.$cordinates_lng;
    }
    if($address){
        $address_string = ' data-address='.$address;
    }
    if($zoom){
        $zoom_string = ' data-zoom='.$zoom;
    }
    if($draggable){
        $draggable_string = ' data-draggable='.$draggable;
    }
    if($marker){
        $marker_string = ' data-marker='.$marker;
    }
    if($maptype){
        $maptype_string = ' data-map-type='.$maptype;
    }
    if($json){
        $json_string = ' data-json='.$json;
    }
    if($parrallax_active == 'true'){
        $parrallaxString = 'data-kepler-parallax="'.$parrallax_active.'" data-depth="'.$parrallax_depth.'"';
    }
    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $data_var = $cordinates_lat_string . $cordinates_lng_string . $address_string . $maptype_string . $zoom_string . $draggable_string . $marker_string . $json_string;
    $result = '<div data-init-plugin="googlemaps"' . ' class="map-wrapper ' . $preset .' kp_' . $id . '" '.$data_var.' '.$parrallaxString.'>';
    $result .= '<div style="height: 100%; width: 100%"  class="map-element"></div>';
    $result .= '</div>';
    return force_balance_tags($result);
}
add_shortcode('kp_map', 'kp_map');
