<?php

/**
 * Fired during plugin activation
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Kepler
 * @subpackage Kepler/includes
 * @author     Revox <support@revox.io>
 */
class Kepler_Activator
{

	public function __construct()
	{
		$this->elementPostType = 'kp_elements';
		$this->elementCategoryTaxonomy = 'kp_element_category';
		$this->importedElementPostType = array(); // stores category taxonomies by term id

		Kepler_Admin::registerElementCategories();
		$this->importElementCategories();
		$this->importElements();
	}

	private function importElementCategories()
	{
		$xml = simplexml_load_file(plugin_dir_path(dirname(__FILE__)) . 'includes/import/kp_element_category.xml');

		foreach ($xml->children() as $item) {
			if (!term_exists($item->Title, $this->elementCategoryTaxonomy)) {
				$title = (string)$item->Title;
				$term = wp_insert_term(
					$title,
					$this->elementCategoryTaxonomy,
					array(
						'description'   => '',
						'slug'          => $item->Slug,
					)
				);
				$this->importedElementPostType[$title] = $term['term_id'];
			}
		}
	}

	private function importElements()
	{	

		$xml = simplexml_load_file(plugin_dir_path(dirname(__FILE__)) . 'includes/import/kp_elements.xml');
		foreach ($xml->children() as $item) {
			if(post_exists("",(string)$item->Content)){
				continue;
			}else{
				$postCreated = array(
					'post_title'    => (string)$item->Title,
					'post_content'  => (string)$item->Content,
					'post_status'   => 'publish',
					'post_type'     => (string)$this->elementPostType,
				);
				$postInsertId = wp_insert_post($postCreated);
	
				// Set custom fields 
				$postOptions = array(
					'kp_data'    => (string)$item->kp_data,
					'kp_element_allow_duplicate' => (string)$item->kp_element_allow_duplicate,
					'kp_element_json_data' => (string)$item->kp_element_json_data,
					'kp_template' => (string)$item->kp_template,
					'kp_element_scale' => (string)$item->kp_element_scale,
				);
				foreach ($postOptions as $key => $value) {
					update_post_meta($postInsertId, $key, $value);
				}
				$category = (string)$item->Categories;
				if (isset($category) && $category !== '') {
					wp_set_object_terms($postInsertId, $this->importedElementPostType[$category],$this->elementCategoryTaxonomy);
				}
			}
		}
	}
}
