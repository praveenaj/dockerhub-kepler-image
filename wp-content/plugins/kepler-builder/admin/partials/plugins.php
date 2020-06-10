<?php

/**
 * Template for Plugins tab of Admin panel
 *
 * @since 1.0
 */
?>
<?php 
    require_once plugin_dir_path( __FILE__ ) . 'tabs.php';
?>
<div class="kepler_theme_plugins an-inside-container theme-browser rendered">
    <?php

    $descriptions = array(
        'kepler-builder' => 'A Revolutionary powerful Wordpress website Builder
        with endless design possibilities.',
    );

    $coreClassNames = array(
        'kepler-builder' => 'Kepler_Builder',
    );

    $isEssentialsInstalled = class_exists('kepler_theme_Core') && class_exists('Kepler_Builder');
    function showPluginStatus($className)
    {
        if (class_exists($className)) {
            echo '<p class="an-label an-active">Active</p>';
        } else {
            echo '<p class="an-label an-inactive">Inactive</p>';
        }
    }
    ?>
    <div class="kepler_theme-portlet kepler_theme-portlet-plugins">
        <div class="kepler_theme-portlet-col kepler_theme-portlet-plugins-intro f-g-3">
            <h2><?php esc_html_e('Essentials','kepler_theme')?></h2>
            <p class=""><?php esc_html_e('Essential components to setup your Kepler experience.','kepler_theme')?></p>
        </div>
        <div class="kepler_theme-portlet-col f-g-7  an-align-center an-align-end">
            <div class="kepler_theme-td-list alt-list-border">
                <div>
                    <div class="left">
                        <div class="plug-thumb" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/img/kepler-builder-thumb.png'; ?>)"></div>
                        <div class="plug-name"><label for="blogname"><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($url) ?>" class="item-name"><?php echo esc_html('Kepler Builder') ?></a></label></div>
                    </div>
                    <div class="right">
                        <div class="desc"><?php echo esc_html($descriptions['kepler-builder']); ?></div>
                        <div class="plug-label"><?php showPluginStatus('Kepler_Builder') ?></div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="kepler_theme-portlet kepler_theme-portlet-plugins">
        <div class="kepler_theme-portlet-col kepler_theme-portlet-plugins-intro f-g-3">
            <h2><?php esc_html_e('Extras','kepler_theme')?></h2>
            <p class=""><?php esc_html_e('Added functionality to improve your experience with Kepler.','kepler_theme')?></p>
        </div>

        <div class="kepler_theme-portlet-col f-g-7  an-align-center an-align-end">
            <div class="kepler_theme-td-list">
                <div>
                    <div class="left">
                        <div class="plug-thumb" style="background-image: url(https://ps.w.org/contact-form-7/assets/icon-256x256.png)"></div>
                        <div class="plug-name"><label for="blogname"><a target="_blank" rel="noopener noreferrer" href="https://contactform7.com/" class="item-name"><?php esc_html_e('Contact Form 7','kepler_theme')?></a></label></div>

                    </div>
                    <div class="right">
                        <div class="desc"><?php esc_html_e('Just another contact form plugin. Simple but flexible.','kepler_theme')?></div>
                        <div class="plug-label"><?php showPluginStatus('WPCF7') ?></div>
                    </div>
                </div>

                <div>
                    <div class="left">
                        <div class="plug-thumb" style="background-image: url(https://ps.w.org/favicon-by-realfavicongenerator/assets/icon-256x256.png)"></div>
                        <div class="plug-name"><label for="blogname"><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/favicon-by-realfavicongenerator/" class="item-name"><?php esc_html_e('Favicon','kepler_theme')?></a></label></div>

                    </div>
                    <div class="right">
                        <div class="desc"><?php esc_html_e('Create and install your favicon for all platforms: PC/Mac of course, but also iPhone/iPad, Android devices, Windows 8 tablets, etc.','kepler_theme')?></div>
                        <div class="plug-label">
                            <?php showPluginStatus('Favicon_By_RealFaviconGenerator')?>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="left">
                        <div class="plug-thumb" style="background-image: url(https://ps.w.org/wp-super-cache/assets/icon-256x256.png)"></div>
                        <div class="plug-name"><label for="blogname"><a target="_blank" rel="noopener noreferrer" href="https://automattic.com/" class="item-name"><?php esc_html_e('WP Super Cache','kepler_theme')?></a></label></div>

                    </div>
                    <div class="right">
                        <div class="desc"><?php esc_html_e('A very fast caching engine for WordPress that produces static html files.','kepler_theme')?></div>
                        <div class="plug-label"><?php showPluginStatus('WP_Super_Cache_Router')  ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>