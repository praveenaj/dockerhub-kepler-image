<?php


/**
 * Kepler Media
 * 
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 */

/**
 * Admin functions used by the builder for media importing and retrieving
 *
 * All functions are registered as wp_ajax in includes/class-kepler.php 
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 * @author     Revox <support@revox.io>
 */
class Kepler_Media {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {

    }

	/**
	 * Get youtube video
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/
	public function kepler_get_youtube_video() {
		$key = 'AIzaSyAigyXHlAFvHgiieZ_Atcmt43sm2g_Oqko';
		$id = $_POST['id'];
		
		$url = 'https://www.googleapis.com/youtube/v3/videos?key=';
		$url .= $key;
		$url .= '&part=snippet&id=';
		$url .= $id;

		$response = wp_remote_get($url);
		$json = json_decode( wp_remote_retrieve_body( $response ), true );
		
		if(empty($json['items'])){
			$return = array(
				'message'=>"video not found",
			);
			wp_send_json_error($return);
			return;
		}
		$snippet = $json['items'][0]['snippet'];
		$video = array(
			'title' => $snippet['title'],
			'thumbnail' => $snippet['thumbnails']['high']['url']
		);

		wp_send_json_success($video);
	}

	/**
	 * Get vimeo video
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/
	public function kepler_get_vimeo_video() {
		$id = $_POST['id'];
		$json = json_decode(file_get_contents("https://vimeo.com/api/v2/video/{$id}.json"));

		if(empty($json[0]->title)){
			$return = array(
				'message'=>"video not found",
			);
			wp_send_json_error($return);
			return;
		}

		$video = array(
			'title' => $json[0]->title,
			'thumbnail' => $json[0]->thumbnail_large
		);

		wp_send_json_success($video);
	}

	/**
	 * Download multiple images to media library
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_download_multi_images(){
		$images = $_POST['images'];
		$imageset = array();
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	

		for ($x = 0; $x < sizeof($images); $x++) {
			$timeout_seconds = 10;
			// Download file to temp dir
			$temp_file = download_url($images[$x], $timeout_seconds );
			if ( !is_wp_error( $temp_file ) ) {
				// Array based on $_FILE as seen in PHP file uploads
				$file = array(
					'name'     => basename($images[$x]), // ex: wp-header-logo.png
					'type'     => 'image/jpeg',
					'tmp_name' => $temp_file,
					'error'    => 0,
					'size'     => filesize($temp_file),
				);

				$overrides = array(
					// Tells WordPress to not look for the POST form
					// fields that would normally be present as
					// we downloaded the file from a remote server, so there
					// will be no form fields
					// Default is true
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
					$title = basename($new_file_path);
					
					$upload_id = wp_insert_attachment( array(
						'guid'           => $new_file_path, 
						'post_mime_type' => 'image/jpeg',
						'post_title'     => $title,
						'post_content'   => '',
						'post_status'    => 'inherit'
					), $new_file_path );
					
					// wp_generate_attachment_metadata() won't work if you do not include this file
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					
					// Generate and save the attachment metas into the database
					$uploaded = wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path));
					
					// Perform any actions here based in the above results
					$obj = array(
						'url' => wp_get_attachment_url($upload_id),
						'data' =>  $upload_id
					);
					array_push($imageset,$obj);
				}

			}
			else{
				$obj = array(
					'url' => null,
					'data' =>  null
				);
				array_push($imageset,$obj);
			}
		}
		wp_send_json_success($imageset);
		return;
	}

	/**
	 * Download unsplash image to media libray
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_download_unsplash(){
		$url = $_POST['url'];
		$title = $_POST['title'];
		$appendExtension = $_POST['extension'];
		if(!$url){
			wp_send_json_error("Error");
			return;
		}
		if(!$appendExtension){
			$url = $url.'.jpg';
		}
		
		require_once( ABSPATH . 'wp-admin/includes/file.php' );

		$timeout_seconds = 10;
		 // Download file to temp dir
		$temp_file = download_url( $url, $timeout_seconds );


		if ( !is_wp_error( $temp_file ) ) {

			 // Array based on $_FILE as seen in PHP file uploads
			$file = array(
				 'name'     => basename($url), // ex: wp-header-logo.png
				'type'     => 'image/jpeg',
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize($temp_file),
			);

			$overrides = array(
				 // Tells WordPress to not look for the POST form
				 // fields that would normally be present as
				 // we downloaded the file from a remote server, so there
				 // will be no form fields
				 // Default is true
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

				// wp_generate_attachment_metadata() won't work if you do not include this file
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate and save the attachment metas into the database
				$uploaded = wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path));
				
				 // Perform any actions here based in the above results
				$return = array(
					'url' => $results['file'],
					'data' =>  $upload_id
				);
				
				wp_send_json_success($return);
				return;
			}

		}
		else{
			
			$error_string = $temp_file->get_error_message();
			wp_send_json_error($error_string);
		}

	}



	/**
	 * Download single image to media libray
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_download_single_image(){
		$url = $_POST['url'];

		if(!$url){
			wp_send_json_error("Error");
			return;
		}

		
		require_once( ABSPATH . 'wp-admin/includes/file.php' );

		$timeout_seconds = 10;
		 // Download file to temp dir
		$temp_file = download_url( $url, $timeout_seconds );


		if ( !is_wp_error( $temp_file ) ) {

			 // Array based on $_FILE as seen in PHP file uploads
			$file = array(
				'name'     => basename($url), // ex: wp-header-logo.png
				'type'     => 'image/jpeg',
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize($temp_file),
			);

			$overrides = array(
				 // Tells WordPress to not look for the POST form
				 // fields that would normally be present as
				 // we downloaded the file from a remote server, so there
				 // will be no form fields
				 // Default is true
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
				$title = basename($new_file_path);
				$upload_id = wp_insert_attachment( array(
					'guid'           => $new_file_path, 
					'post_mime_type' => 'image/jpeg',
					'post_title'     => $title,
					'post_content'   => '',
					'post_status'    => 'inherit'
				), $new_file_path );

				// wp_generate_attachment_metadata() won't work if you do not include this file
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate and save the attachment metas into the database
				$uploaded = wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path));
				
				 // Perform any actions here based in the above results
				$return = array(
					'url' => $results['file'],
					'data' =>  $upload_id
				);
				
				wp_send_json_success($return);
				return;
			}

		}
		else{
			$return = array(
				'url' => null,
				'data' =>  null
			);
			wp_send_json_success($return);
			return;
			// $error_string = $temp_file->get_error_message();
			// wp_send_json_error($error_string);
		}

	}




	public function kepler_get_media_info($attachment) {

		$attachment = $_POST['media_id'];
		$attachedFile = get_attached_file( $attachment );
		$meta = wp_get_attachment_metadata( $attachment );

		if($attachedFile == null || $meta == null){
			$return = array(
				'message'=>"error while getting media"
			);
			wp_send_json_error($return);
			return;
		}
		$return = array(
			'fileName' => wp_basename( $attachedFile ),
			'fileSize' => size_format(filesize( $attachedFile )),
			'width' => $meta['width'],
			'height' => $meta['height'],
			'uploadedDate' => get_post_time('d-m-Y', true, $attachment)
		);

		wp_send_json_success($return);
	}

	/**
	 * Custom Upload image to media libray from builder
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_upload_image(){
		$upload_dir = wp_upload_dir();
	    $upload_path = $upload_dir['path'] . DIRECTORY_SEPARATOR;
	    $num_files = count($_FILES['file']['tmp_name']);

	    $newupload = 0;

	    if ( !empty($_FILES) ) {
	        $files = $_FILES;
	        foreach($files as $file) {
	            $newfile = array (
	                    'name' => $file['name'],
	                    'type' => $file['type'],
	                    'tmp_name' => $file['tmp_name'],
	                    'error' => $file['error'],
	                    'size' => $file['size']
	            );

	            $_FILES = array('upload'=>$newfile);
	            foreach($_FILES as $file => $array) {
	                $newupload = media_handle_upload( $file, 0 );
	            }
	        }
	    }
	    $return = array(
			'message'=>'File Uploaded',
			'path' => wp_get_attachment_url($newupload)
		);
		wp_send_json_success($return);
	}


	/**
	 * Delete multiple images from media libray
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_delete_images(){
		$ids = $_POST['ids'];

	    if ( !empty($ids) ) {
	        foreach($ids as $id) {
	            wp_delete_attachment($id, 'true' );
	        }
	    }
	    $return = array(
			'message'=>'Media deleted',
			'ids' => $ids
		);
		wp_send_json_success($return);
	}


	/**
	 * GET logo info
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/
	public function kepler_get_logo_info() {

		$attachment = get_theme_mod( 'custom_logo' );
		$attachedFile = get_attached_file( $attachment );
		$meta = wp_get_attachment_metadata( $attachment );

		if($attachedFile == null || $meta == null){
			$return = array(
				'message'=>"error while getting logo"
			);
			wp_send_json_error($return);
			return;
		}
		$return = array(
			'fileName' => wp_basename( $attachedFile ),
			'fileSize' => size_format(filesize( $attachedFile )),
			'width' => $meta['width'],
			'height' => $meta['height'],
			'uploadedDate' => get_post_time('d-m-Y', true, $attachment),
			'url' =>  wp_get_attachment_url($attachment)
		);

		wp_send_json_success($return);
	}

	/**
	 * SET logo from builder
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_set_logo() {

		$mediaId = $_POST['id'];

		set_theme_mod( 'custom_logo', $mediaId );

		if($mediaId == null){
			$return = array(
				'message'=>"error while updating logo"
			);
			wp_send_json_error($return);
			return;
		}
		$return = array(
			'message' => 'logo updated'
		);

		wp_send_json_success($return);
	}    
}