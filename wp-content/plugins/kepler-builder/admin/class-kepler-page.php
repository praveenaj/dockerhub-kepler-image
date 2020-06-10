<?php


/**
 * Kepler Pages
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 */

/**
 * Admin functions used by the builder for importing/viewing/saving pages 
 * and Master layouts
 *
 * All functions are registered as wp_ajax in includes/class-kepler.php 
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 * @author     Revox <support@revox.io>
 */
class Kepler_PageUtil {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {

	}

	public function kepler_get_all_pages_content() {
		$wpPages = get_pages();
		$pageShortcodeList=[];
		foreach( $wpPages as $page ) {      
			$content = $page->post_content;
			if ( ! $content ) // Check for empty page
				continue;
		   array_push($pageShortcodeList,$content);
		}
		wp_send_json_success($pageShortcodeList);
	}
	
	/**
	 * Save Page
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
    public function kepler_get_page_content() {
		// global $wpdb; // this is how you get access to the database
		//update_site_option('kepler_page_styles', '');
		$pageId = intval( $_POST['page_id'] );
		$content = get_post($pageId);

		$stylekits = get_posts( array(
			'post_type' => 'kp_stylekit',
			'post_status' => array('publish')  
		));		
		
		if(!stylekits){
			$id = get_site_option('kepler_current_style_kit');
			$stylekit = get_post($id);
		}
		$userStyles = get_site_option('kepler_page_styles');
		$return = array(
			'page_id' => $pageId,
			'content' => $content->post_content,
			'pageTitle' => $content->post_title,
			'type' => $content->post_status,
			'userStyles' => stripcslashes($stylekit->post_content),
			'mdate' => $content->post_modified_gmt,
			// 'page' => $content
		);
		wp_send_json_success($return);
	}
	
    /**
	 * GET Page meta
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/
    public function kepler_get_page_meta() {
		$pageId = intval( $_POST['page_id'] );
		$json = get_post_meta($pageId, 'kepler_page_json', true);
		$page = !empty($json) ? $json : "[]";
		$return = array(
			'content' => $page
		);
		wp_send_json_success($return);
	}

    /**
	 * SET Page meta
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/	
	public function kepler_set_page_meta() {
		$pageId = intval( $_POST['page_id'] );
		$json = $_POST['json'];
		if ( !add_post_meta($pageId, 'kepler_page_json', $json, true) ) {
		   update_post_meta($pageId, 'kepler_page_json', $json);
		}

		$return = array(
			'message'=>'Page Saved'
		);
		wp_send_json_success($return);
	}
	
	/**
	 * Save Page
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/
    public function kepler_set_page_content() {
		$pageId = intval( $_POST['page_id'] );
		$content = $_POST['content'];
		$type = $_POST['type'];
		$stylekit = $_POST['stylekit'];
	  	$post = array(
	      'ID'           => $pageId,
		  'post_content' => $content,
		  );   
		wp_update_post( $post );
		if($type == "draft"){
			$this->resetStylekit();
			$kit = array(
				'ID'           => $stylekit["ID"],
				'post_content' => $stylekit["STYLES"],
				'post_status' => 'publish'
				);
				wp_update_post( $kit );
				update_post_meta( $stylekit["ID"], 'var',$stylekit["VAR"]);
				update_post_meta( $stylekit["ID"], 'cssstrting', $stylekit["STYLE_STRING"] );
				update_post_meta( $stylekit["ID"], 'icon_type', $stylekit["ICONS_TYPE"] );
			$save_log = "Page Saved Draft";
		}
		else{
			$this->resetStylekit();
			$kit = array(
				'ID'           => $stylekit["ID"],
				'post_content' => $stylekit["STYLES"],
				);
			wp_update_post( $kit );

			$kit = get_post($stylekit["ID"]);
			$cssfilename = get_post_meta( $kit->ID, 'css_file', true );
			$css = get_post_meta( $kit->ID, 'css_file', true );
			//small
			update_post_meta( $kit->ID, 'var',$stylekit["VAR"]);
			//big not needed
			update_post_meta( $kit->ID, 'cssstrting', $stylekit["STYLE_STRING"] );
			//small
			update_post_meta( $kit->ID, 'icon_type', $stylekit["ICONS_TYPE"] );
			//small
			update_site_option('kepler_current_style_kit', $kit->ID);
			//small
			update_site_option('kepler_page_required_fonts', json_encode(json_decode(stripslashes($stylekit["VAR"]))->fontsRequired) );
			//small
			update_site_option('kepler_style_kit_css', $css);
			//small
			update_site_option('kepler_style_kit_icons', $stylekit["ICONS_TYPE"]);
			//big
			if($stylekit["LOCAL_STYLES"] && gettype($stylekit["LOCAL_STYLES"]) === 'string'){
				update_site_option('kepler_local_styles',$stylekit["LOCAL_STYLES"]);
				$this->update_local_style_revision($stylekit["LOCAL_STYLES"]);
			}
			//save to css
			$save_log = $this->save_custom_user_styles($stylekit["STYLE_STRING"],$cssfilename);
			if($save_log != null){
				$save_log = "Page Saved Publish";
			}
		}
		$return = array(
			'message'=> $save_log
		);
		wp_send_json_success($return);
	}

	/**
	 * Save Page content onyl
	 *
	 * @return		JSON
	 * @since		1.0.6
	*/

	public function kepler_set_page_content_only(){
		$pageId = intval( $_POST['page_id'] );
		$content = $_POST['content'];
		$post = array(
			'ID'           => $pageId,
			'post_content' => $content,
		);  
		wp_update_post( $post );
		$return = array(
			'message'=> "Page Conent Saved"
		);
		wp_send_json_success($return);
	}
	/**
	 * Save style string onyl
	 *
	 * @return		JSON
	 * @since		1.0.6
	*/
	public function kepler_set_style_string_only(){

		$this->resetStylekit();
			$kit = array(
				'ID'           => $_POST['stylekit_id'],
				'post_content' => $_POST['stylekit_styles'],
			);
		wp_update_post( $kit );

		$return = array(
			'message'=> "Style String Saved"
		);
		wp_send_json_success($return);
	}
	/**
	 * Save style css onyl
	 *
	 * @return		JSON
	 * @since		1.0.6
	*/
	public function kepler_set_styleKit_css_only(){
		$kitID = $_POST['stylekit_id'];
		$stylekit_VAR = $_POST['stylekit_var'];
		$stylekit_ICONS_TYPE = $_POST['stylekit_icon_type'];
		$stylekit_STYLE_STRING = $_POST['stylekit_style_string'];

		$cssfilename = get_post_meta( $kitID, 'css_file', true );
		$css = get_post_meta( $kitID, 'css_file', true );
		update_post_meta( $kitID, 'var',$stylekit_VAR);
		update_post_meta( $kitID, 'cssstrting', $stylekit_STYLE_STRING );
		update_post_meta( $kitID, 'icon_type', $stylekit_ICONS_TYPE );
		update_site_option('kepler_current_style_kit', $kitID);
		update_site_option('kepler_page_required_fonts', json_encode(json_decode(stripslashes($stylekit_VAR))->fontsRequired) );
		update_site_option('kepler_style_kit_css', $css);
		update_site_option('kepler_style_kit_icons', $stylekit_ICONS_TYPE);
		$save_log = $this->save_custom_user_styles($stylekit_STYLE_STRING,$cssfilename);

		$return = array(
			'message'=> "Style CSS Saved"
		);
		wp_send_json_success($return);
	} 
	/**
	 * Save style local only
	 *
	 * @return		JSON
	 * @since		1.0.6
	*/
	public function kepler_set_local_css_only(){
		$stylekit_Localstyles=$_POST['stylekit_LocalStyles'];
		if($stylekit_Localstyles && gettype($stylekit_Localstyles) === 'string'){
			update_site_option('kepler_local_styles',$stylekit_Localstyles);
			$this->update_local_style_revision($stylekit_Localstyles);
		}
		$return = array(
			'message'=> "Local Styles Saved"
		);
		wp_send_json_success($return);
	} 

	


	public function update_local_style_revision($local_styles){

		$posts = get_posts([
			'post_type' => 'kp_local_styles',
			'post_status' => 'publish',
			'numberposts' => -1
		  ]);
		  

		if( sizeof($posts) === 0 ) {
			$local_styles_post = wp_insert_post(
				array(
					'comment_status'	=>	'closed',
					'ping_status'		=>	'closed',
					'post_author'		=>	1,
					'post_name'		=>	'local_styles',
					'post_title'		=>	'local_styles',
					'post_status'		=>	'publish',
					'post_content' => $local_styles,
					'post_type'		=>	'kp_local_styles'
				)
			);
		} else {
			// var_dump($posts[0]->ID);
			$local_styles_post = array(
				'ID'           => $posts[0]->ID,
				'post_content' => $local_styles,
				'post_status' => 'publish'
				);
				wp_update_post( $local_styles_post );
		} // end 
	}
	
	/**
	 * GET Header content from WP
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/
    public function kepler_get_header_content() {
		$footers = new WP_Query(
		    array(
		        'post_type' => 'kp_header_blocks',
		        'posts_per_page' => -1,
		        'post_status' => 'publish'
		    )
		);

		$pageId = intval( $footers->posts[0]->ID );
		$content = get_post($pageId);
		$userStyles = get_site_option('kepler_page_styles');
		$return = array(
			'content' => $content->post_content,
			'userStyles' => $userStyles
		);
		wp_send_json_success($return);
	}

	/**
	 * GET Footer content from WP
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/
	public function kepler_get_footer_content() {
		$headers = new WP_Query(
		    array(
		        'post_type' => 'kp_footer_blocks',
		        'posts_per_page' => -1,
		        'post_status' => 'publish'
		    )
		);

		$pageId = intval( $headers->posts[0]->ID );
		$content = get_post($pageId);
		$userStyles = get_site_option('kepler_page_styles');
		$return = array(
			'content' => $content->post_content,
			'userStyles' => $userStyles
		);
		wp_send_json_success($return);
    }
    
	/**
	 * GET all master layouts
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/
	public function kepler_get_compositions_list(){
		$compositions = new WP_Query(
		    array(
		        'post_type' => 'kp_block_composition',
		        'posts_per_page' => -1,
		        'post_status' => 'publish'
		    )
		);

		$posts = array();
        foreach($compositions->posts as $key => $value){
            $posts[] = array(
                'id' => $value->ID,
                'url' => '#designer/static/'.$value->ID,
                'title' => $value->post_title
            );
        }

		$return = array(
			'list' => $posts
		);
		wp_send_json_success($return);
    }
    
  	/**
	 * GET master layout
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/  	
	public function kepler_get_composition() {
		$compId = intval( $_POST['comp_id'] );
		$userStyles = get_site_option('kepler_page_styles');
		$headerId = get_post_meta($compId, 'kepler_comp_header_id', true);
		$footerId = get_post_meta($compId, 'kepler_comp_footer_id', true);

		$title = get_post_field( 'post_title', $compId );

		$header = get_post($headerId);
		$footer = get_post($footerId);

		$return = array(
			'header' => $header->post_content,
			'footer' => $footer->post_content,
			'pageTitle' => $title,
			'userStyles' => stripcslashes($userStyles)
		);

		wp_send_json_success($return);
	}


	/**
	 * DELETE composition
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/  	
	public function kepler_delete_composition() {
		$compId = intval( $_POST['comp_id'] );

		// Can't use inbuilt backbone's model.destroy() due to bug in JS API.
		// https://github.com/WordPress/gutenberg/issues/3215
		wp_delete_post($compId);

		$return = array(
			'message' => 'Composition deleted!'
		);

		wp_send_json_success($return);
	}

	/**
	 * SET Master Layout
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/ 
	public function kepler_set_composition() {
		$compId = intval( $_POST['comp_id'] ); 
		$headerContent = $_POST['header'];
		$type = $_POST['type'];
		$footerContent = $_POST['footer'];
		$stylekit = $_POST['stylekit'];
		$activeStyle = (int)get_site_option('kepler_current_style_kit');

		//combine these calls
		$headerId = get_post_meta($compId, 'kepler_comp_header_id', true);
		$footerId = get_post_meta($compId, 'kepler_comp_footer_id', true);

	  	$headerPost = array(
	      'ID'           => $headerId,
	      'post_content' => $headerContent,
	  	);
	  	$footerPost = array(
	      'ID'           => $footerId,
	      'post_content' => $footerContent,
	  	);

		// Update the post into the database
		wp_update_post( $headerPost );
		wp_update_post( $footerPost );

		if($type == "draft"){
			$this->resetStylekit();
			$kit = array(
				'ID'           => $stylekit["ID"],
				'post_content' => $stylekit["STYLES"],
				'post_status' => 'publish'
				);
				wp_update_post( $kit );
				update_post_meta( $stylekit["ID"], 'var',$stylekit["VAR"]);
				update_post_meta( $stylekit["ID"], 'user_styles', $stylekit["USER_STYLES"] );

			$save_log = "Composition Saved Draft";
			
		}
		else{
			$this->resetStylekit();
			$kit = array(
				'ID'           => $stylekit["ID"],
				'post_content' => $stylekit["STYLES"],
				);
			wp_update_post( $kit );

			$kit = get_post($stylekit["ID"]);
			$cssfilename = get_post_meta( $kit->ID, 'css_file', true );
			$css = get_post_meta( $kit->ID, 'css_file', true );
			update_post_meta( $kit->ID, 'var',$stylekit["VAR"]);
			update_post_meta( $kit->ID, 'cssstrting', $stylekit["STYLE_STRING"] );
			update_post_meta( $kit->ID, 'icon_type', $stylekit["ICONS_TYPE"] );
			update_site_option('kepler_current_style_kit', $kit->ID);
			update_site_option('kepler_page_required_fonts', json_encode(json_decode(stripslashes($stylekit["VAR"]))->fontsRequired ));
			update_site_option('kepler_style_kit_css', $css);
			update_site_option('kepler_style_kit_icons', $stylekit["ICONS_TYPE"]);
			if($stylekit["LOCAL_STYLES"] && gettype($stylekit["LOCAL_STYLES"]) === 'string'){
				update_site_option('kepler_local_styles',$stylekit["LOCAL_STYLES"]);
			}
			$save_log = $this->save_custom_user_styles($stylekit["STYLE_STRING"], $css);
			if($save_log != null){
				$save_log = "Composition Saved Publish";
			}
		}
		

		$return = array(
			'message'=> $save_log
		);
		wp_send_json_success($return);
	}

	/**
	 * SET Composition Content
	 *
	 * @return		JSON callback
	 * @since		1.0.7
	 */ 
	public function kepler_set_composition_content() {
		$compId = intval( $_POST['comp_id'] ); 
		$headerContent = $_POST['header'];
		$footerContent = $_POST['footer'];

		//combine these calls
		$headerId = get_post_meta($compId, 'kepler_comp_header_id', true);
		$footerId = get_post_meta($compId, 'kepler_comp_footer_id', true);

		$headerPost = array(
			'ID'           => $headerId,
			'post_content' => $headerContent,
			);
			$footerPost = array(
			'ID'           => $footerId,
			'post_content' => $footerContent,
			);

		  // Update the post into the database
		wp_update_post( $headerPost );
		wp_update_post( $footerPost );
	}
	/**
	 * SET Composition Style 
	 *
	 * @return		JSON callback
	 * @since		1.0.7
	 */ 
	public function kepler_set_composition_style_string() {
		$stylekit = $_POST['stylekit'];

		$this->resetStylekit();
		$kit = array(
			'ID'           => $_POST['stylekit_id'],
			'post_content' => $_POST['stylekit_styles'],
		);
		wp_update_post( $kit );
		$return = array(
			'message'=> "Style String Saved"
		);
		wp_send_json_success($return);
	}
	/**
	 * SET Composition Style Css
	 *
	 * @return		JSON callback
	 * @since		1.0.7
	 */ 
	public function kepler_set_composition_style_css() {
		$kitID = $_POST['stylekit_id'];
		$stylekit_VAR = $_POST['stylekit_var'];
		$stylekit_ICONS_TYPE = $_POST['stylekit_icon_type'];
		$stylekit_STYLE_STRING = $_POST['stylekit_style_string'];

		$kit = get_post($kitID);
		$css = get_post_meta( $kitID, 'css_file', true );
		update_post_meta( $kitID, 'var',$stylekit_VAR);
		update_post_meta( $kitID, 'cssstrting', $stylekit_STYLE_STRING );
		update_post_meta( $kitID, 'icon_type', $stylekit_ICONS_TYPE);
		update_site_option('kepler_current_style_kit', $kitID);
		update_site_option('kepler_page_required_fonts', json_encode(json_decode(stripslashes($stylekit_VAR))->fontsRequired ));
		update_site_option('kepler_style_kit_css', $css);
		update_site_option('kepler_style_kit_icons', $stylekit_ICONS_TYPE);
		$save_log = $this->save_custom_user_styles($stylekit_STYLE_STRING, $css);
		$return = array(
			'message'=> "Style CSS Saved"
		);
		wp_send_json_success($return);
	}
	/**
	 * SET Composition Local Css
	 *
	 * @return		JSON callback
	 * @since		1.0.7
	 */ 
	public function kepler_set_composition_local_css() {
		$stylekit_Localstyles=$_POST['stylekit_LocalStyles'];
		if($stylekit_Localstyles && gettype($stylekit_Localstyles) === 'string'){
			update_site_option('kepler_local_styles',$stylekit_Localstyles);
		}
		$return = array(
			'message'=> "Local Styles Saved"
		);
		wp_send_json_success($return);
	}

	/**
	 * SET global master layout
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/ 
	public function kepler_set_default_composition() {
		$compId = intval( $_POST['comp_id'] );
		
		update_site_option('kepler_default_composition', $compId);

		$return = array(
			'message'=> 'default composition set'
		);
		wp_send_json_success($return);
	}

	/**
	 * IMPORT master layout from Kepler API
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/ 
	public function kepler_import_composition() {
		$header = $_POST['header'];
		$footer = $_POST['footer'];
		$stylekit = $_POST['stylekit'];
		if(!$header || !$footer){
			wp_send_json_error("Error");
			return;
		}
		//Header
		$newHeader = array(
			'post_title' => "New Header",
			'post_type' => 'kp_header_blocks',
			'post_content' => (string)$header,
			'post_status'   => 'publish'
		);
		$newHeaderId = wp_insert_post($newHeader);
		//Footer
		$newFooter = array(
			'post_title' => "New Footer",
			'post_type' => 'kp_footer_blocks',
			'post_content' => (string)$footer,
			'post_status'   => 'publish'
		);
		$newFooterId = wp_insert_post($newFooter);
		//Composition
		$newComposition = array(
			'post_title' => "New Layout",
			'post_type' => 'kp_block_composition',
			'post_status'   => 'publish'
		);
		$newCompositionId = wp_insert_post(wp_slash($newComposition));
		add_post_meta($newCompositionId,
		'kepler_comp_header_id',$newHeaderId
		);
		add_post_meta($newCompositionId,
		'kepler_comp_footer_id',$newFooterId
		);

		
		if(!empty($stylekit)){
			$kit = array(
				'ID'           => $stylekit["ID"],
				'post_content' => $stylekit["STYLES"],
				'post_status' => 'publish'
				);
			wp_update_post( $kit );
			update_site_option('kepler_current_style_kit', $kit->ID);
			update_post_meta( $stylekit["ID"], 'cssstrting', $stylekit["STYLE_STRING"] );
			if($stylekit["LOCAL_STYLES"] && gettype($stylekit["LOCAL_STYLES"]) === 'string'){
				update_site_option('kepler_local_styles',$stylekit["LOCAL_STYLES"]);
			}
			$this->save_custom_user_styles($stylekit["STYLE_STRING"], $css);
		}

		wp_send_json_success(array(
			'id'=>$newCompositionId,
			'message' => 'Composition Imported'
		));
	}

	/**
	 * IMPORT single page from Kepler API
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/ 
	public function kepler_import_single_page() {
		$page_name = $_POST['page_name'];
		$shortcode = $_POST['shortcode'];
		$stylekit = $_POST['stylekit'];
		//Add new Page
		$postCreated = array(
			'post_title'    => (string)$page_name,
			'post_content'  => (string)$shortcode,
			'post_status'   => 'publish',
			'post_type'     => 'page', 
		);
		$postInsertId = wp_insert_post( $postCreated );
		
		//Save Stylekit
		$this->resetStylekit();
		$kit = array(
			'ID'           => $stylekit["ID"],
			'post_content' => $stylekit["STYLES"],
			);
		wp_update_post( $kit );

		$kit = get_post($stylekit["ID"]);
		$cssfilename = get_post_meta( $kit->ID, 'css_file', true );
		if($stylekit["LOCAL_STYLES"] && gettype($stylekit["LOCAL_STYLES"]) === 'string'){
			update_site_option('kepler_local_styles',$stylekit["LOCAL_STYLES"]);
		}
		$save_log = $this->save_custom_user_styles($stylekit["STYLE_STRING"],$cssfilename);

		wp_send_json_success(array(
			'id'=>$postInsertId,
			'message' => 'Page Imported'
		));
	}

	/**
	 * IMPORT multiple pages from Kepler API
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_import_pages() {
		$pages = $_POST['pages'];
		$stylekit = $_POST['stylekit'];
		$i = 0;
		$lastPostId = "";
		foreach ($pages as $page) {
			$postCreated = array(
				'post_title'    => (string)$page["DisplayName"],
				'post_content'  => (string)$page["shorcode_Json"],
				'post_status'   => 'publish',
				'post_type'     => 'page', 
			);
			if (strcmp((string)$page["DisplayName"],"Home") == 0) {
				$postInsertId = wp_insert_post( $postCreated );
			}else{
				$lastPostId = wp_insert_post( $postCreated );
			}
			$i++;
		}

		if(empty($postInsertId)){
			$postInsertId = $lastPostId;
		}

		$kit = array(
			'ID'           => $stylekit["ID"],
			'post_content' => $stylekit["STYLES"],
			'post_status' => 'publish'
			);
		wp_update_post( $kit );
		update_site_option('kepler_current_style_kit', $kit->ID);
		update_post_meta( $stylekit["ID"], 'cssstrting', $stylekit["STYLE_STRING"] );
		if($stylekit["LOCAL_STYLES"] && gettype($stylekit["LOCAL_STYLES"]) === 'string'){
			update_site_option('kepler_local_styles',$stylekit["LOCAL_STYLES"]);
		}
		$this->save_custom_user_styles($stylekit["STYLE_STRING"], $css);


		$header = $_POST['header'];
		$footer = $_POST['footer'];
		if(!$header || !$footer){
			wp_send_json_error("Error No header footer data");
			return;
		}
		//Header
		$newHeader = array(
			'post_title' => "Default Header",
			'post_type' => 'kp_header_blocks',
			'post_content' => (string)$header,
			'post_status'   => 'publish'
		);
		$newHeaderId = wp_insert_post($newHeader);
		//Footer
		$newFooter = array(
			'post_title' => "Default Footer",
			'post_type' => 'kp_footer_blocks',
			'post_content' => (string)$footer,
			'post_status'   => 'publish'
		);
		$newFooterId = wp_insert_post($newFooter);

		//Composition
		$newComposition = array(
			'post_title' => "Default Layout",
			'post_type' => 'kp_block_composition',
			'post_status'   => 'publish'
		);
		$newCompositionId = wp_insert_post(wp_slash($newComposition));
		add_post_meta($newCompositionId,
		'kepler_comp_header_id',$newHeaderId
		);
		add_post_meta($newCompositionId,
		'kepler_comp_footer_id',$newFooterId
		);

		update_site_option('kepler_default_composition', $newCompositionId);
		update_option('kepler_wizard_ran', true);

		wp_send_json_success(array(
			"id" => $postInsertId,
			'message' => 'Demo content imported'
		));
	}
	/**
	 * IMPORT CSSOBJ pages from Kepler API
	 *
	 * @return		JSON callback
	 * @since		1.0.5
	*/
	public function kepler_import_stylekitObj() {
		$stylekit = $_POST['stylekit'];

		$kit = array(
			'ID'           => $stylekit["ID"],
			'post_content' => $stylekit["STYLES"],
			'post_status' => 'publish'
			);
		wp_update_post( $kit );
		update_site_option('kepler_current_style_kit', $kit->ID);
		update_post_meta( $stylekit["ID"], 'cssstrting', $stylekit["STYLE_STRING"] );
		if($stylekit["LOCAL_STYLES"] && gettype($stylekit["LOCAL_STYLES"]) === 'string'){
			update_site_option('kepler_local_styles',$stylekit["LOCAL_STYLES"]);
		}
		$this->save_custom_user_styles($stylekit["STYLE_STRING"], $css);

		wp_send_json_success(array(
			"status" => "success",
			'message' => 'CSSOBJ imported'
		));
	}


	/**
	 * IMPORT CSSOBJ pages from Kepler API
	 *
	 * @return		JSON callback
	 * @since		1.0.5
	*/
	public function kepler_import_HFComposition() {

		$header = $_POST['header'];
		$footer = $_POST['footer'];
		if(!$header || !$footer){
			wp_send_json_error("Error No header footer data");
			return;
		}
		//Header
		$newHeader = array(
			'post_title' => "Default Header",
			'post_type' => 'kp_header_blocks',
			'post_content' => (string)$header,
			'post_status'   => 'publish'
		);
		$newHeaderId = wp_insert_post($newHeader);
		//Footer
		$newFooter = array(
			'post_title' => "Default Footer",
			'post_type' => 'kp_footer_blocks',
			'post_content' => (string)$footer,
			'post_status'   => 'publish'
		);
		$newFooterId = wp_insert_post($newFooter);

		//Composition
		$newComposition = array(
			'post_title' => "Default Layout",
			'post_type' => 'kp_block_composition',
			'post_status'   => 'publish'
		);
		$newCompositionId = wp_insert_post(wp_slash($newComposition));
		add_post_meta($newCompositionId,
		'kepler_comp_header_id',$newHeaderId
		);
		add_post_meta($newCompositionId,
		'kepler_comp_footer_id',$newFooterId
		);

		update_site_option('kepler_default_composition', $newCompositionId);

		wp_send_json_success(array(
			"status" => "success",
			'message' => 'Composition imported'
		));
	}

	/**
	 * IMPORT a sigle page from Kepler API
	 *
	 * @return		JSON callback
	 * @since		1.0.4
	*/
	public function kepler_import_page() {

		$page = $_POST['page'];
		$lastPostId = "";
		$isHome=false;
		$postCreated = array(
			'post_title'    => (string)$page["DisplayName"],
			'post_content'  => (string)$page["shorcode_Json"],
			'post_status'   => 'publish',
			'post_type'     => 'page', 
		);
		if (strcmp((string)$page["DisplayName"],"Home") == 0) {
			$isHome=true;
			$postInsertId = wp_insert_post( $postCreated );
		}else{
			$lastPostId = wp_insert_post( $postCreated );
		}
		

		if(empty($postInsertId)){
			$postInsertId = $lastPostId;
		}

		update_option('kepler_wizard_ran', true);

		wp_send_json_success(array(
			"id" => $postInsertId,
			"isHomePage"=>$isHome,
			'message' => 'Demo content imported'
		));

	}
	
	/**
	 * IMPORT download all image in page from Kepler API
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_download_page_images() {
		$images = $_POST['images'];
		$wpImages =array();
		foreach ($images as $image) {
			$data = $this->downloadImages($image["url"],$image["title"],$image["extention"]);
			array_push($wpImages,$data);
		}
		return $wpImages;
	}

	/**
	 * Download images from Kepler API
	 * @access		private
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	private function downloadImages($url,$title,$appendExtension){
		if(!$url){
		 return "error";
		}
		if(!$appendExtension){
			$url = $url.'.jpg';
		}
		
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
 
		 $timeout_seconds = 10;
		 // Download file to temp dir
		 $temp_file = download_url( $url, $timeout_seconds );

 
		 if ( !is_wp_error( $temp_file ) ) {
			 $file = array(
				 'name'     => basename($url), // ex: wp-header-logo.png
				 'type'     => 'image/jpeg',
				 'tmp_name' => $temp_file,
				 'error'    => 0,
				 'size'     => filesize($temp_file),
			 );
 
			 $overrides = array(
				 'test_form' => false,
				 // Setting this to false lets WordPress allow empty files, not recommended
				 // Default is true
				 'test_size' => true,
			 );
 
			 // Move the temporary file into the uploads directory
			 $results = wp_handle_sideload( $file, $overrides );

			 if ( !empty( $results['error'] ) ) {
				 wp_send_json_error("Error Downloading");
				 // Insert any error handling here
			 } else {
				 
				$new_file_path = $results["file"];
				 $upload_id = wp_insert_attachment( array(
					'guid'           => $new_file_path, 
					'post_mime_type' => 'image/jpeg',
					'post_title'     => $title,
					'post_content'   => '',
					'post_status'    => 'inherit'
				), $new_file_path );
			 
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				// Generate and save the attachment metas into the database
				$uploaded = wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path));
				 // Perform any actions here based in the above results
				$return = array(
					'url' => $results['file'],
					'data' =>  $upload_id
				);
				 return $return;
			 }

		 }
		 else{
			$error_string = $temp_file->get_error_message();
			return $error_string;
		 }
	}

	/**
	 * Rest stylekits
	 * @access		private
	 * @since		1.0.0
	*/
    private function resetStylekit(){
		$stylekits = get_posts( array(
			'post_type' => 'kp_stylekit',
			'post_status' => array('publish')  
		));		
		foreach ( $stylekits as $stylekit ){
			$kit = array(
				'ID'           => $stylekit->ID,
				'post_status' => 'draft'
				);
			wp_update_post( $kit );
		}
	}

	/**
	 * Save css to wp_uploads/kepler_styles
	 * @param		$styles - css string, $cssfilename - filename
	 * @access		private
	 * @since		1.0.0
	*/
	private function save_custom_user_styles($styles,$cssfilename){
		$access_type = get_filesystem_method();
		if($access_type === 'direct')
		{
			/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
			$creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());

			/* initialize the API */
			if ( ! WP_Filesystem($creds) ) {
				/* any problems and we exit */
				return false;
			}

			global $wp_filesystem;
			$destination = wp_upload_dir();
			$destination_path = $destination['basedir'];
			if ( ! $wp_filesystem->put_contents($destination_path.$cssfilename, stripslashes_deep($styles),FS_CHMOD_FILE) ) {
				return 'error saving file!';
			}
		}
		else
		{
			add_action('admin_notices', array($this, 'admin_notice_wp_uploads'));
		}
	}

	/**
	 * Admin notice to get write access on wp_uploads
	 * Used for downloading stylekits.
	 * @access		private
	 * @since		1.0.0
	*/
	private function admin_notice_wp_uploads(){
		$class = 'notice notice-error';
		$message = __( 'Please allow write access to wp-uploads directory.', 'kepler-builder' );
	
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}
}
