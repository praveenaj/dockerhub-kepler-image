<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

/**
 * Manage dynamic footers 
 *
 * @since 1.0
 */
class kepler_theme_Footer {
	/**
	 * Constructor.
	 *
	 * @access  public
	 */
	public function __construct() {
		
	}

	/**
	 * Returns footer content based on current page.
	 *
	 * @access  public
	 */
	public function kepler_theme_footer_content(){
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
		$footerId =  get_post_meta( $comp->ID, 'kepler_comp_footer_id', true );
		$footer = get_post( $footerId );
		$output =  $footer->post_content;
		return $output;

	}

}
