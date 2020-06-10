<?php 
/**
 * Template for tab links
 *
 * @since 1.0
 */
$showModal = get_option('kepler_wizard_ran');
$pageID = get_option('page_on_front');
if ( $pageID == "0" ){
    $page = get_page_by_title('Home');
    if ($page->ID){
        $pageID = $page->ID;
    }else{
        $pageID = false;
    }
}
?>

<div class="kepler_theme-plugin-container">
    <div class="plugin-header">
        <div class="an-inside-container d-flex an-align-center an-jus-space-between">
                <div class="kepler_theme-logo"></div>
                <?php 
                if(get_option('envato_purchase_code_kepler') && get_option('kepler_current_style_kit')){
                    if($pageID){
                        $url = add_query_arg('kepler_builder', '1', get_permalink($pageID));
                ?>
                <a href="<?php echo $url; ?>" target="_blank" class="btn-kepler-builder-open default logo-icon guttern">Open Editor</a>
                <?php 
                    }else{ 
                ?>
                <a href="https://help.kepler.app/knowledgebase/how-to-open-editor/" target="_blank" class="btn-kepler-builder-open default logo-icon guttern">How to Open Editor</a>
                <?php
                    }
                }
                ?>
        </div>
    </div>
    <div class="an-inside-container kepler_theme-tab-holder">

        <div class="an-full-wd">
            <div class="an-full-wd">
                <div class="d-flex">
                        <?php 
                            $selected = isset ( $_GET['page'] ) ? $_GET['page'] : 'kepler_builder_overview';

                            if(get_option('envato_purchase_code_kepler') && get_option('kepler_current_style_kit')){
                        ?>
                        <a class="an-nav-tab <?php echo esc_attr($selected == 'kepler_builder_overview' ? 'nav-tab-active' : ''); ?>"
                            href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'kepler_builder_overview' ), 'admin.php' ) ) ); ?>">
                            Overview
                        </a>
                        <a class="an-nav-tab <?php echo esc_attr($selected == 'kepler_builder_install' ? 'nav-tab-active' : ''); ?>"
                            href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'kepler_builder_install' ), 'admin.php' ) ) ); ?>">
                            Installed Plugins
                        </a>
                        <a class="an-nav-tab <?php echo esc_attr($selected == 'kepler_builder_settings' ? 'nav-tab-active' : ''); ?>"
                            href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'kepler_builder_settings' ), 'admin.php' ) ) ); ?>">
                            Settings
                        </a>
                        <a class="an-nav-tab <?php echo esc_attr($selected == 'kepler_builder_help' ? 'nav-tab-active' : ''); ?>"
                            href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'kepler_builder_help' ), 'admin.php' ) ) ); ?>">
                            Help?
                        </a>
                        <?php }
                        else{ ?>
                        <a class="an-nav-tab <?php echo esc_attr($selected == 'kepler_builder' ? 'nav-tab-active' : ''); ?>"
                            href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'kepler_builder' ), 'admin.php' ) ) ); ?>">
                            Getting Started
                        </a>    
                                     
                        <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
	
