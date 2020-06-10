<?php
/**
 * Template part for displaying default header
 * For usage notes refer to header.php 
 * @package kepler_theme
 */

?>
<section class="default-header">
    <div class="container header-wrapper">
       <div class="search-overlay">
           <div class="search-overlay-inner">
                <div class="container">
                        <?php get_search_form(); ?>
                </div>
           </div>
       </div>
        <div class="menu-wrapper-outer">
            <div class="logo">
                <a href="<?php echo get_home_url(); ?>">
                <?php
                if (has_custom_logo()) {
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

                    echo '<img src="' . esc_url($logo[0]) . '">';
                } else {
                    echo '<h4>' . get_bloginfo('name') . '</h4>';
                }
                ?>
                </a>
            </div>
            <div class="menu-wrapper">
                <div id="main-nav" class="main-nav-wrapper">
                    <?php 

                    $args = array(
                        'theme_location' => 'main-menu',
                        'container_class' => 'menu',    
                    );
                    wp_nav_menu($args); 
                    ?>
                    <button class="menu-toggle"><?php echo kepler_theme_get_icon('kp_icon_more_horizontal') ?></button>
                    <div class="menu-hidden">
                        <ul class='hidden-links hidden'></ul>
                    </div>
                    <div id="search-overlay-show" class="search-overlay-show">
                        <?php echo kepler_theme_get_icon("kp_icon_search"); ?>
                    </div>
                </div>
            </div>
           
            <button id="mobile-menu-toggle" class="mobile-menu-toggle"><?php echo kepler_theme_get_icon('kp_icon_menu'); ?></button>

            <div id="mobile-nav" class="mobile-nav">
                <div class="mobile-nav-inner">
                        <button class="mobile-menu-toggle"><?php echo kepler_theme_get_icon('kp_icon_close'); ?></button>
                        <div class="search-wrapper">
                            <?php get_search_form(); ?>
                        </div>
                        <?php 

                        $args = array(
                            'theme_location' => 'main-menu',
                            'container_class' => 'menu',    
                            'menu_class' => ''
                        );
                        wp_nav_menu($args); 
                        ?>
                    </div>
                </div>
        </div>
    </div>
</section>