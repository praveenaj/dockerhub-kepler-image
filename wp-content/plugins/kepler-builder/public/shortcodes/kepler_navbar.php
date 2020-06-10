<?php
/**
 * Kepler Builder Shortcodes for Altspace theme
 * WP Logo Shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
    exit( 'Direct script access denied.' );
}

/**
 * Get nav bar items
 *
 * @access public
 * @since 1.0.0
 * @param integer     $menuID     ID of WP menu.
 * @return string
 */
function kp_getNavItems($menuID) {
    $primaryNav = wp_get_nav_menu_items($menuID);

    return $primaryNav;
}

/**
 * Custom walker class.
 */
class WPDocs_Walker_Nav_Menu extends Walker_Nav_Menu {
    function __construct($presetClass) {
        $this->presetClass = $presetClass;
    }
    /**
     * Starts the list before the elements are added.
     *
     * Adds classes to the unordered list sub-menus.
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        // Depth-dependent classes.
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
            'sub-menu',
            ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
            ( $display_depth >=2 ? 'sub-sub-menu' : '' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode( ' ', $classes );
 
        // Build HTML for output.
        $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
    }
 
    /**
     * Start the element output.
     *
     * Adds main/sub-classes to the list items and links.
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
 
        // Depth-dependent classes.
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
            ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
            'menu-item-depth-' . $depth
        );
        $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );
 
        // Passed classes.
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
 
        // Build HTML.
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';
 
        // Link attributes.
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ' class="menu-link ' . $this->presetClass .' ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
 
        // Build HTML output and pass through the proper filter.
        $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
            $args->before,
            $attributes,
            $args->link_before,
            apply_filters( 'the_title', $item->title, $item->ID ),
            $args->link_after,
            $args->after
        );
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/**
 * Kepler Navbar Shortcode
 *
 * @access public
 * @since 1.0.0
 * @param array     $params     settings attribute for shortcode.
 * @param string    $content    content inside shortcode
 * @return string
 */
function kp_navbar( $params, $content=null ) {
    extract( shortcode_atts( array(
        'preset' => '',
        'childpreset' => '',
        'id' => '',
        'menuid' => '',
        'visibility' => ''
    ), $params ) );

    $emptyClass = '';
    $menuItems = kp_getNavItems($menuid);

    if(isset($_GET['kepler_builder_preview']) && !is_array($menuItems)) {
        $emptyClass = ' data-is-empty="true"';
    }


    $result = '<nav id="kp_' . $id . '" class="navbar navbar-expand-lg  kp_' . $id . '" '.$emptyClass. ' data-visibility="'. $visibility.'" data-type="navbar">';
    $closeClass = '';
    if($visibility =="mobile"){
        $closeClass = "hidden";
        $result .='<a href="#" class="menu-toggler disable-pointer" data-nav-toggle="true">'.do_shortcode("[kp_icon preset='' inherit_color='false' iconClass='kp_icon_menu'][/kp_icon]") .'</a>';
    }

    $result .=  '<div class="navbar-collapse '.$closeClass.' menu">';
    if($visibility =="mobile"){
        $result .='<a href="" class="close disable-pointer" data-nav-close="true">'.do_shortcode("[kp_icon preset='' inherit_color='false' iconClass='kp_icon_close'][/kp_icon]") .' </a>';
    }

    $result .= wp_nav_menu( array( 'menu' => $menuid, 'menu_class'=>'navbar-nav', 'echo' => false, 'walker' => new WPDocs_Walker_Nav_Menu($childpreset) ) );

    $result .=  '</div>';
    $result .= '</nav>';

    return force_balance_tags( $result );

}
add_shortcode('kp_navbar', 'kp_navbar');
