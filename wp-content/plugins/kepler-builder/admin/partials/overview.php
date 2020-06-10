<?php

/**
 * Template for overview tab of Admin panel
 *
 * @since 1.0
 */
$showModal = get_option('kepler_wizard_ran');
?>

<?php 
    require_once plugin_dir_path( __FILE__ ) . 'tabs.php';
?>
<div class="kepler_theme_plugins an-inside-container theme-browser rendered">
    <div class="themes wp-clearfix">
        <?php if ($showModal == '1') { ?>
        <div class="kepler_theme-portlet an-pd-fix kepler_theme-portlet-kb">
            <div class="overview-card">
                <div class="cr-header">
                    <p><?php esc_html_e('Learn','kepler_theme')?><p>
                    <h3><?php esc_html_e('Tutorials','kepler_theme')?></h3>
                </div>
                <div class="cr-footer">
                    <p class="desc-card"><?php esc_html_e('View videos and articles to help you get started with Kepler Theme.','kepler_theme')?><p>
                    <a target="_blank" href="https://help.kepler.app/knowledgebase_category/how-tos-tutorials/" class="button button-primary"><?php esc_html_e('View How-Tos','kepler_theme')?></a>
                </div>
            </div>
            <div class="overview-card">
                <div class="cr-header">
                    <p><?php esc_html_e('Learn','kepler_theme')?><p>
                    <h3><?php esc_html_e('Knowledge Base','kepler_theme')?></h3>
                </div>
                <div class="cr-footer">
                    <p class="desc-card"><?php esc_html_e('In-depth articles & discussions on everything about Kepler.','kepler_theme')?><p>
                    <a target="_blank" href="https://help.kepler.app/knowledgebase/" class="button button-default"><?php esc_html_e('Read','kepler_theme')?></a>
                </div>
            </div>
            <div class="overview-card">
                <div class="cr-header">
                    <p><?php esc_html_e('Help','kepler_theme')?><p>
                    <h3><?php esc_html_e('Community','kepler_theme')?></h3>
                </div>
                <div class="cr-footer">
                    <p class="desc-card"><?php esc_html_e('Get help from our community or submit a ticket.','kepler_theme')?><p>
                    <a target="_blank" href="https://help.kepler.app/" class="button button-default"><?php esc_html_e('Visit','kepler_theme')?></a>
                </div>
            </div>
        </div>
        <div class="kepler_theme-portlet kepler_theme-portlet-col kepler_theme-portlet-community">
            <p class="overline"><?php esc_html_e('Community','kepler_theme')?></p>
            <div class="kepler_theme-portlet-row">

                <div class="cr-header left">

                    <h3 class="request-title"><?php esc_html_e('Got a missing feature, a bug, or a request? We have a request box.','kepler_theme')?></h3>
                </div>
                <div class="kepler_theme-portlet-community-right">
                    <div class="center">
                        <p class="request-decs"><?php esc_html_e('We continuously work hard to improve the quality and performance of the Kepler experience. We value every bit of customer input and we have a special box for any requests, bug reports, or new template suggestions.','kepler_theme')?></p>
                        <a target="_blank" href="https://help.kepler.app/" class="button button-default"><?php esc_html_e('Go to Request Box','kepler_theme')?></a>

                    </div>
                    <div class="right">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/img/ideas_matter_c.png'; ?>" alt="Ideas matter" srcset="<?php echo get_stylesheet_directory_uri() . '/img/ideas_matter_c@2x.png'; ?> 2x" />
                    </div>
                </div>

            </div>
        </div>
        <?php } else { ?>
        <!--dummy content! -->
        <div class="kepler_theme-portlet dummy-wireframe">
            <div class="overview-card">
                <div class="cr-header">
                    <div class="dummy dummy2"></div>
                    <div class="dummy dummy1"></div>
                </div>
                <div class="cr-footer">
                    <div class="dummy dummy3"></div>
                    <div class="dummy dummy3"></div>
                    <div class="dummy dummy2"></div>
                    <div class="dummy dummybtn"></div>
                </div>
            </div>

            <div class="overview-card">
                <div class="cr-header">
                    <div class="dummy dummy2"></div>
                    <div class="dummy dummy1"></div>
                </div>
                <div class="cr-footer">
                    <div class="dummy dummy3"></div>
                    <div class="dummy dummy3"></div>
                    <div class="dummy dummy2"></div>
                    <div class="dummy dummybtn"></div>
                </div>
            </div>

            <div class="overview-card">
                <div class="cr-header">
                    <div class="dummy dummy2"></div>
                    <div class="dummy dummy1"></div>
                </div>
                <div class="cr-footer">
                    <div class="dummy dummy3"></div>
                    <div class="dummy dummy3"></div>
                    <div class="dummy dummy2"></div>
                    <div class="dummy dummybtn"></div>
                </div>
            </div>

            <div class="overview-card">
                <div class="cr-header">
                    <div class="dummy dummy2"></div>
                    <div class="dummy dummy1"></div>
                </div>
                <div class="cr-footer">
                    <div class="dummy dummy3"></div>
                    <div class="dummy dummy3"></div>
                    <div class="dummy dummy2"></div>
                    <div class="dummy dummybtn"></div>
                </div>
            </div>
        </div>
        <!--dummy content end -->
        <?php } ?>
    </div>
</div>
<?php if ($showModal != '1') { ?>
<div class="an-modal-center">
    <div class="setup-container welcome-panel ">
        <h1>Congratulations! You have successfully installed Kepler</h1>
        <p class="about-description">Let's launch into Kepler Website Builder. Click "Get Started" below to begin your next website.</p>
    </div>

    <video class="an-setup-video" preload="metadata" poster="https://storage.googleapis.com/kepler-marketing/theme/welcome_c.jpg" loop="" autoplay="" muted="" playsinline="" class="hero-img" width="490">
        <source src="https://storage.googleapis.com/kepler-marketing/theme/welcome_c.mp4" class="" type="video/mp4">
        <source src="https://storage.googleapis.com/kepler-marketing/theme/welcome_c.ogv" type="video/ogv">
        <source src="https://storage.googleapis.com/kepler-marketing/theme/welcome_c.webm" type="video/webm">
    </video>

    <div class="an-setup-footer-container">
        <div class="an-setup-footer">
            <div class="an-setup-button migrate kp-get-started">
                <a target="_blank" href="<?php echo get_site_url() . '?kepler_builder=1#welcome' ?>" id="kepler_theme__btn-install" class="button button-primary button-hero btn-install dashicons-before dashicons-migrate">Get Started with Kepler Builder</a>
            </div>
        </div>
    </div>
</div>
<?php } ?>