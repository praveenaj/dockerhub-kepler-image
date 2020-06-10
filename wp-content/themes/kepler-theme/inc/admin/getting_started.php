<?php

/**
 * Template for default tab of Admin panel
 *
 * @since 1.0
 */

?>

<div class="kepler_theme-plugin-container">
    <div class="plugin-header">
        <div class="an-inside-container d-flex an-align-center">
                <div class="kepler_theme-logo"></div>
        </div>
    </div>
    <div class="an-inside-container kepler_theme-tab-holder-getting-started">

        <div class="an-full-wd">
            <div class="an-full-wd">
                <div class="d-flex">
                        <a class="an-nav-tab <?php echo esc_attr($selected == 'kepler_theme' ? 'nav-tab-active' : ''); ?>"
                            href="<?php echo esc_url(admin_url(add_query_arg(array( 'page' => 'kepler_theme' ), 'themes.php'))); ?>">
                            <?php _e('Getting Started','kepler_theme') ?>
                        </a>
                        <a class="an-nav-tab <?php echo esc_attr($selected == 'kepler_theme' ? 'nav-tab-active' : ''); ?>"
                            href="<?php echo esc_url(admin_url(add_query_arg(array( 'page' => 'kepler_theme','tab'=>'plugins' ), 'themes.php'))); ?>">
                            <?php _e('Plugins','kepler_theme') ?>
                        </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($_GET['tab']) and $_GET['tab'] =='plugins'): ?>
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
                <h2>Essentials</h2>
                <p class="">Essential components to setup your Kepler theme experience.</p>
            </div>
            <div class="kepler_theme-portlet-col f-g-7  an-align-center an-align-end">
                <div class="kepler_theme-td-list alt-list-border">
                    <div>
                        <div class="left">
                            <div class="plug-thumb" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/img/kepler-builder-thumb.png'; ?>)"></div>
                            <div class="plug-name"><label for="blogname"><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url("https://kepler.app/") ?>" class="item-name"><?php echo esc_html('Kepler Builder','kepler_theme') ?></a></label></div>
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
                <h2>Extras</h2>
                <p class="">Added functionality to improve your experience with the Hello Kepler Theme.</p>
            </div>

            <div class="kepler_theme-portlet-col f-g-7  an-align-center an-align-end">
                <div class="kepler_theme-td-list">
                    <div>
                        <div class="left">
                            <div class="plug-thumb" style="background-image: url(https://ps.w.org/contact-form-7/assets/icon-256x256.png)"></div>
                            <div class="plug-name"><label for="blogname"><a target="_blank" rel="noopener noreferrer" href="https://contactform7.com/" class="item-name">Contact Form 7</a></label></div>

                        </div>
                        <div class="right">
                            <div class="desc">Just another contact form plugin. Simple but flexible.</div>
                            <div class="plug-label"><?php showPluginStatus('WPCF7') ?></div>
                        </div>
                    </div>

                    <div>
                        <div class="left">
                            <div class="plug-thumb" style="background-image: url(https://ps.w.org/favicon-by-realfavicongenerator/assets/icon-256x256.png)"></div>
                            <div class="plug-name"><label for="blogname"><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/favicon-by-realfavicongenerator/" class="item-name">Favicon</a></label></div>

                        </div>
                        <div class="right">
                            <div class="desc">Create and install your favicon for all platforms: PC/Mac of course, but also iPhone/iPad, Android devices, Windows 8 tablets, etc.</div>
                            <div class="plug-label"><?php
                                                    showPluginStatus('Favicon_By_RealFaviconGenerator')
                                                    ?></div>
                        </div>
                    </div>

                    <div>
                        <div class="left">
                            <div class="plug-thumb" style="background-image: url(https://ps.w.org/wp-super-cache/assets/icon-256x256.png)"></div>
                            <div class="plug-name"><label for="blogname"><a target="_blank" rel="noopener noreferrer" href="https://automattic.com/" class="item-name">WP Super Cache</a></label></div>

                        </div>
                        <div class="right">
                            <div class="desc">A very fast caching engine for WordPress that produces static html files.</div>
                            <div class="plug-label"><?php showPluginStatus('WP_Super_Cache_Router')  ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else:?>
<div class="kepler_theme_plugins an-inside-container theme-browser rendered">
    <div class="themes wp-clearfix">
        <div class="kepler_theme-portlet an-pd-fix kepler_theme-portlet-kb kp-primary-light discover-kp-builder">
            <div class="overview-card intro-txt">
                <div class="cr-header">
                    <p>Discover<p>
                            <h2>Get the Most Out<br> of Your Theme</h3>
                            <p class="desc-card">The Hello Kepler theme is all about building creative, beautiful and professional websites with the world's first exclusive access to the Kepler WordPress Builder, the most advanced website builder on WordPress.<p>

                </div>
                <div class="cr-footer">
                            <a target="_blank" href="<?php echo esc_url('https://kepler.app/builder/index.php?demo=binarydots&stylekit=core&ref=hellokepler') ?>" class="button button-primary kp-btn-lg">Try Kepler Builder</a>
                            <p class="sm-text">Try it FREE. No sign-up required<p>

                </div>
            </div>
            <div class="overview-card intro-video">
                <div class="kelper-builder-video">
                    <video preload="metadata" poster="https://storage.googleapis.com/kepler-marketing/theme/kepler-quickview_c.jpg" loop="" autoplay="" muted="" playsinline="" width="560">
                        <source src="https://storage.googleapis.com/kepler-marketing/theme/kepler-quickview_c.mp4" class="" type="video/mp4">
                        <source src="https://storage.googleapis.com/kepler-marketing/theme/kepler-quickview_c.ogv" type="video/ogv">
                        <source src="https://storage.googleapis.com/kepler-marketing/theme/kepler-quickview_c.webm" type="video/webm">
                    </video>
                </div>
            </div>
        </div>
        <div class="anthem-row">
            <div class="anthem-column kepler_theme-portlet kepler_theme-portlet-col kp-stylekit-card">
                <div class="kepler_theme-portlet-row">

                    <div class="cr-header left">
                        <h2 class="request-title">Introducing <br> Website Style Filters</h2>
                        <a target="_blank" href="<?php echo esc_url('https://kepler.app?ref=hellokepler') ?>" class="button">See All Style Filters</a>
                    </div>
                    <div class="kepler_theme-portlet-community-right">
                        <p class="desc-card">Use the all new Website Style Filters in Kepler to instantly switch up your website's look and feel and find a combination that's just right.<p>
                    </div>
                </div>
                
                <div class="text-center anthem-stylekit-animation">
                <video preload="metadata" poster="https://storage.googleapis.com/kepler-marketing/theme/website-filters.jpg" loop="" autoplay="" muted="" playsinline="" width="470">
                        <source src="https://storage.googleapis.com/kepler-marketing/theme/website-filters.mp4" class="" type="video/mp4">
                        <source src="https://storage.googleapis.com/kepler-marketing/theme/website-filters.ogv" type="video/ogv">
                        <source src="https://storage.googleapis.com/kepler-marketing/theme/website-filters.webm" type="video/webm">
                    </video>
                </div>
            </div>
            <div class="anthem-column  kepler_theme-portlet kepler_theme-portlet-col kepler_theme-portlet-faq">
                        <h3 class="request-title">Frequently Asked Questions</h3>


                        <div class="kp-tab">
                        <input id="kp-tab-r1" type="radio" name="kptab" checked="checked" >
                        <label for="kp-tab-r1">Is there community support for Hello Kepler Theme?</label>
                        <div class="kp-tab-content">
                            <p>
                            As a user of the Kepler Theme, you have access to plenty of free content at the <a href="<?php echo esc_url('https://help.kepler.app') ?>">Kepler Help Center</a>. If you wish to receive additional premium support you can choose to purchase the Kepler Builder as an add-on to your Hello Kepler theme.
                            </p>
                        </div>
                        </div>
                        <div class="kp-tab">
                        <input id="kp-tab-r2" type="radio" name="kptab">
                        <label for="kp-tab-r2">Where can I report a bug in the theme?</label>
                        <div class="kp-tab-content">
                            <p>
                            You can report bugs at the <a href="<?php echo esc_url('https://help.kepler.app') ?>">Kepler Help Center</a> or by email <a href="mailto:support@revox.io">here</a>.
                            </p>
                        </div>
                        </div>

                        <div class="kp-tab">
                        <input id="kp-tab-r3" type="radio" name="kptab">
                        <label for="kp-tab-r3">Can I sell the Hello Kepler theme?</label>
                        <div class="kp-tab-content">
                            <p>
                            The Hello Kepler theme is distributed under the GNU License recommended by the WordPress Community. As such it cannot be resold As Is or in any derivative form and will remain a Free Software.
                            </p>
                        </div>
                        </div>

                        <div class="kp-tab">
                        <input id="kp-tab-r4" type="radio" name="kptab">
                        <label for="kp-tab-r4">Does the Hello Kepler Theme work with other builder apart from Kepler Builder?</label>
                        <div class="kp-tab-content">
                            <p>
                            The Hello Kepler theme works best with the Kepler Builder and is designed to showcase all of the advanced options available to you when combined. It is also compatible with Gutenberg and native WordPress elements.
                            </p>
                        </div>
                        </div>


                        <div class="kp-tab">
                        <input id="kp-tab-r5" type="radio" name="kptab">
                        <label for="kp-tab-r5">How can I contact the author?</label>
                        <div class="kp-tab-content">
                            <p>
                            You can reach out to us at the <a href="<?php echo esc_url('https://help.kepler.app') ?>">Kepler Help Center</a> or by email <a href="mailto:support@revox.io">here</a>.
                            </p>
                        </div>
                        </div>
            </div>
        </div>
        
        <div class="kepler_theme-portlet-row anthem-footer">

            <div class="left">
                We continuously work hard to improve the quality and performance of the Kepler experience. We value every bit of customer input and we have a special box for any requests, bug reports, or new template suggestions.
            </div>
            <div class="right">
                <a href="<?php echo esc_url('https://help.kepler.app') ?>">Go to Community</a>
            </div>
        </div>
    </div>
</div>

<?php endif;?>