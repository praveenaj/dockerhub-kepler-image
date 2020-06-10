<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * Header Element Shortcodes
 */
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
    exit( 'Direct script access denied.' );
}
/**
 * Kepler Header Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_header( $params, $content=null ) {
    extract( shortcode_atts( array(
        'id' => '',
        'preset' => '',
        'position' => '',
        'multilayeredbg' => '',
        'multilayeredbgorder' => '',
        'headerposition' =>'',
        'stickyheader' =>''
    ), $params ) );
    $stickyheaderstring = '';
    if(strcmp($stickyheader,"false") !== 0){
        $stickyheaderstring = 'header-sticky';
    }
    $content = preg_replace( '/<br class="nc".\/>/', '', $content );
    $result = '<div class="header '  . $preset . ' kp_' . $id . '  header-'.$headerposition.' '.$stickyheaderstring.'" data-position="'. $position .'" data-kepler="header">';
    if($multilayeredbg) {
        $multilayeredbgorder = explode(' ', $multilayeredbgorder);
        foreach($multilayeredbgorder as $key => $val){
            $bgContent = '';
            $result .= '<div class="'. $val .' multiLayered">'.$bgContent.'</div>';
        }
    }
    $result .= do_shortcode( $content );
    $result .= '</div>';
    return force_balance_tags( $result );
}
add_shortcode( 'kp_header', 'kp_header' );


/**
 * Kepler Footer Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_footer( $params, $content=null ) {
    extract( shortcode_atts( array(
        'id' => '',
        'preset' => '',
        'multilayeredbg' => '',
        'multilayeredbgorder' => '',
    ), $params ) );
    
    $content = preg_replace( '/<br class="nc".\/>/', '', $content );
    $result = '<div class="section  ' . $preset . ' kp_' . $id . '" data-kepler="footer">';
    if($multilayeredbg) {
        $multilayeredbgorder = explode(' ', $multilayeredbgorder);
        foreach($multilayeredbgorder as $key => $val){
            $bgContent = '';
            $result .= '<div class="'. $val .' multiLayered">'.$bgContent.'</div>';
        }
    }
    $result .= do_shortcode( $content );
    $result .= '</div>';
    return force_balance_tags( $result );
}
add_shortcode( 'kp_footer', 'kp_footer' );
