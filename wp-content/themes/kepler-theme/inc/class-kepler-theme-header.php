<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

/**
 * Manages dynamic headers
 *
 * @since 1.0
 */
class kepler_theme_Header {
	/**
	 * Constructor.
	 *
	 * @access  public
	 */
	public function __construct() {
		
	}

	/**
	 * Returns header content based on current page.
	 *
	 * @access  public
	 */
	public function kepler_theme_header_content(){
		global $post;
		if($post){
			$pageId = $post->ID;
			$compId = get_post_meta( $pageId, 'kepler_composition_id', true );
			if($compId == ''){
				$compId = get_site_option('kepler_default_composition');
			}
		}else{
			$compId = get_site_option('kepler_default_composition');
		}

		if(!$compId) return false;
		
		$comp  =  get_post( $compId );
		$headerId =  get_post_meta( $comp->ID, 'kepler_comp_header_id', true );

		$header = get_post( $headerId );

		$output =  $header->post_content;
		return $output;
	}

}
