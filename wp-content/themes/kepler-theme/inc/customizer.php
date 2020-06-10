<?php
/**
 * kepler_theme Theme Customizer
 *
 * @package kepler_theme
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function kepler_theme_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'page_header_section' , array(
        'title'    => __( 'Page Header', 'kepler_theme' ),
        'priority' => 30
    ) );   

    $wp_customize->add_setting( 'page_header_color_setting' , array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback'  => 'esc_attr'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
        'label'    => __( 'Header Color', 'kepler_theme' ),
        'section'  => 'page_header_section',
        'settings' => 'page_header_color_setting',
    ) ) );

    $wp_customize->add_setting( 'invert_text_color',
        array(
            'default' => 0,
            'transport' => 'refresh',
            'sanitize_callback'  => 'esc_attr'
        )
    );
    
    $wp_customize->add_control( 'invert_text_color',
        array(
            'label' => __( 'Invert text colors', 'kepler_theme' ),
            'description' => esc_html__( 'All colors in the header will invert to match the background','kepler_theme' ),
            'section'  => 'page_header_section',
            'priority' => 10, // Optional. Order priority to load the control. Default: 10
            'type'=> 'checkbox',
            'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
        )
    );

    $wp_customize->add_setting( 'page_header_setting_image',
        array(
            'default' => '',
            'transport' => 'refresh',
            'sanitize_callback' => 'esc_attr'
        )
    );
 
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'page_header_setting_image',
        array(
            'label' => __( 'Header Background Image','kepler_theme' ),
            'description' => esc_html__( 'Select or change page header background image','kepler_theme' ),
            'section' => 'page_header_section',
            'mime_type' => 'image',  // Required. Can be image, audio, video, application, text
            'button_labels' => array( // Optional
                'select' => __( 'Select File','kepler_theme' ),
                'change' => __( 'Change File','kepler_theme' ),
                'default' => __( 'Default','kepler_theme' ),
                'remove' => __( 'Remove','kepler_theme' ),
                'placeholder' => __( 'No file selected','kepler_theme' ),
                'frame_title' => __( 'Select File','kepler_theme' ),
                'frame_button' => __( 'Choose File','kepler_theme' ),
            )
        )
    ));

    $wp_customize->add_setting( 'hide_page_header',
        array(
            'default' => 0,
            'transport' => 'refresh',
            'sanitize_callback' => 'absint'
        )
    );

    $wp_customize->add_control( 'hide_page_header',
        array(
            'label' => __( 'Hide Page header', 'kepler_theme' ),
            'description' => esc_html__( 'Hide page header in all pages','kepler_theme' ),
            'section'  => 'page_header_section',
            'priority' => 10, // Optional. Order priority to load the control. Default: 10
            'type'=> 'checkbox',
            'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
        )
    );
}
add_action( 'customize_register', 'kepler_theme_customize_register' );

function kepler_theme_customize_css()
{
    $pageHeaderColor = get_theme_mod('page_header_color_setting');
    $pageHeaderImage = get_theme_mod('page_header_setting_image');
    $invertTextColor = get_theme_mod('invert_text_color');
    $hidePageHeader = get_theme_mod('hide_page_header');
    
    $pageHeaderStyleString = "";
    $pageHeaderInnerStyleString = "";
    $pageHeaderChildrenStyleString = "";
    if($pageHeaderColor){
        $pageHeaderStyleString .= "background-color:$pageHeaderColor;"; 
    }
    if($pageHeaderImage){
        $mediaURL = wp_get_attachment_url($pageHeaderImage);
        $pageHeaderInnerStyleString = "background-image:url($mediaURL);"; 
    }
    if($invertTextColor){
        $pageHeaderChildrenStyleString .= "color:#fff !important"; 
    }
    if($hidePageHeader){
        $pageHeaderStyleString .= "display:none"; 
    }


?>  
         <style type="text/css" id="atlspace-custom-css">
            .entry-content.content .page-header { 
                <?php echo esc_html($pageHeaderStyleString) ?>
            }
            .entry-content.content .page-header .inner{ 
                <?php echo esc_html($pageHeaderInnerStyleString) ?>
            }
            .entry-content.content .page-header *{ 
                <?php echo esc_html($pageHeaderChildrenStyleString) ?>
            }
         </style>
<?php
 
}
add_action( 'wp_head', 'kepler_theme_customize_css');