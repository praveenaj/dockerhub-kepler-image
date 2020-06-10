<?php


/**
 * Kepler Navigation
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 */

/**
 * Admin functions used by the builder for CRUD operations of WP nav
 *
 * All functions are registered as wp_ajax in includes/class-kepler.php 
 *
 * @package    Kepler
 * @subpackage Kepler/admin
 * @author     Revox <support@revox.io>
 */
class Kepler_Nav {

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
	 * Create WP menu  - builder interface
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_generate_nav() {
		// new nav element dropped to canvas. create a new menu with 5 items
		$menu_name = 'kp_menu_'. wp_generate_uuid4();
		$menuID = wp_create_nav_menu($menu_name);

		$return = array(
			'menu_id' => $menuID
		);

		wp_send_json_success($return);
	}

	private function buildTree(array &$flatNav, $parentId = 0) {
		$branch = [];
	
		foreach ($flatNav as &$navItem) {
		  if($navItem->menu_item_parent == $parentId) {
			$children = $this->buildTree($flatNav, $navItem->ID);
			if($children) {
			  $navItem->children = $children;
			}
	
			$branch[$navItem->menu_order] = $navItem;
			unset($navItem);
		  }
		}
	
		return $branch;
	}
	
	/**
	 * GET WP menu bar  - builder interface
	 *
	 * @return		JSON
	 * @since		1.0.0
	*/
	public function kepler_get_nav() {

		$menuID = $_POST['menuId'];
				
		$primaryNav = wp_get_nav_menu_items($menuID);

		// menu deleted from wp-admin 
		if($primaryNav == null){
			$return = array(
				'message'=> 'error while retrieving menu'
			);
			wp_send_json_error($return);
			return;
		}
		foreach($primaryNav as $nav) {
			$originalPageTitle = get_the_title((int)$nav->object_id);
			$nav->originalPageTitle = $originalPageTitle ;
			$nav->menuId = $menuID ;
		}

		$return = array(
			'nav' => $this->buildTree($primaryNav),
			'menu_id' => $menuID
		);

		wp_send_json_success($return);
	}

	/**
	 * ADD menu items to menu  - builder interface
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_add_nav_item() {
		$menuID = $_POST['menuId'];
		$pageId = $_POST['pageId'];
		$url = $_POST['url'];
		$pageTitle = $_POST['pageTitle'];

		$menuOptions = array(
			'menu-item-title' => $pageTitle,
			'menu-item-status' => 'publish'
		);

		if($url){
			$menuOptions['menu-item-url'] = $url;
			$menuOptions['menu-item-type'] = 'custom'; // optional
		} else {
			$menuOptions['menu-item-object'] =  'page';
			$menuOptions['menu-item-object-id'] = $pageId;
			$menuOptions['menu-item-type'] = 'post_type';
			
		}

		$callback = wp_update_nav_menu_item($menuID, 0, $menuOptions);
		if($callback->errors){
			$menu_name = 'kp_menu_'. wp_generate_uuid4();
			$menuID = wp_create_nav_menu($menu_name);
			//Try again
			$callback = wp_update_nav_menu_item($menuID, 0, $menuOptions);
			$newMenu = true;
		}

		$return = array(
			'message' => $callback,
			'newMenu' =>$newMenu,
			'menuId' => $menuID,
			'$url' => $url,
			'$menuOptions' => $menuOptions
			// 'isNewMenu' => $newMenu

		);

		wp_send_json_success($return);
	}

	/**
	 * Update menu list order in WP menu - builder interface
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_update_nav() {
		$menuID = $_POST['menuId'];
		$navItems = $_POST['sortedNavItems'];

		$nav = wp_get_nav_menu_items($menuID);

		if($nav == null || sizeof($nav) == 0){
			$return = array(
				'message'=> 'error while retrieving nav'
			);
			wp_send_json_error($return);
			return;
		}

		$index = array();
		$parentIds = [];
		$navIter = 1;

		function setParentIds(&$navItems, &$parentIds, &$index, $parentId = "0") {
			global $navIter;

			foreach($navItems as $key => $val) {
				$navIter = $navIter + 1;
				$index[$val['db_id']] = $navIter;
				$parentIds[$val['db_id']] = $parentId ;
				if($val['children']) {
					setParentIds($val['children'], $parentIds, $index, $val['db_id']);
				} 
		   }

		}
		

		setParentIds($navItems, $parentIds, $index);
		
		foreach($nav as $menu_item) {
			$args = array(
				'menu-item-db-id' => $menu_item->db_id, 
				'menu-item-object-id' => $menu_item->object_id, 
				'menu-item-object' => $menu_item->object, 
				'menu-item-position' => $index[$menu_item->db_id], 
				'menu-item-type' => $menu_item->type, 
				'menu-item-title' => $menu_item->title, 
				'menu-item-url' => $menu_item->url, 
				'menu-item-description' => $menu_item->description, 
				'menu-item-attr-title' => $menu_item->attr_title, 
				'menu-item-target' => $menu_item->target, 
				'menu-item-classes' => implode(' ', $menu_item->classes), 
				'menu-item-xfn' => $menu_item->xfn, 
				'menu-item-status' => $menu_item->post_status
			);
			if($parentIds[$menu_item->db_id]) {
				$args['menu-item-parent-id'] = (int)$parentIds[$menu_item->db_id];
			}
			
			wp_update_nav_menu_item( $menuID, $menu_item->db_id, $args );
		}

		$return = array(
			'message' => 'nav updated successfully',
			'parentIds' => $parentIds,
			'index' => $index
			
		);

		wp_send_json_success($return);
	}

	/**
	 * Delete menu item in WP menu - builder interface
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_delete_nav_item() {
		$id = $_POST['menuItemId'];

		wp_delete_post($id);

		$return = array(
			'message' => 'nav item deleted'
		);

		wp_send_json_success($return);
	}

	/**
	 * Update menu label in WP menu - builder interface
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_update_nav_item_label() {
		$id = $_POST['id'];
		$label = $_POST['label'];
		$menuId = $_POST['menuId'];
		$url = $_POST['url'];

		$nav = wp_get_nav_menu_items($menuId);
		$menuItem = null;
		
		foreach($nav as $menu_item) {
			if ($id == $menu_item->db_id) {
				$menuItem = $menu_item;
				break;
			}
		}

		if(!$menuItem) {
			$return = array(
				'message'=> 'invalid menu id'
			);
			wp_send_json_error($return);
			return;
		}

		$menuOptions = array(
			'menu-item-title' => $label,
			'menu-item-status' => 'publish',
			'menu-item-position' => $menuItem->menu_order,
		);

		if($url){
			$menuOptions['menu-item-url'] = $url;
			$menuOptions['menu-item-type'] = 'custom'; // optional
		} else {
			$menuOptions['menu-item-object'] =  'page';
			$menuOptions['menu-item-object-id'] = $menuItem->object_id;
			$menuOptions['menu-item-type'] = 'post_type';
			
		}

		$menuOptions['menu-item-parent-id'] = $menuItem->menu_item_parent;

		wp_update_nav_menu_item($menuId, $id, $menuOptions);

		$return = array(
			'message' => 'nav item label updated'
		);

		wp_send_json_success($return);
	}

	/**
	 * Delete WP menu - builder interface
	 *
	 * @return		JSON callback
	 * @since		1.0.0
	*/
	public function kepler_delete_nav() {
		$id = $_POST['menuId'];

		wp_delete_nav_menu($id);

		$return = array(
			'message' => 'nav deleted'
		);

		wp_send_json_success($return);
	}
}
?>