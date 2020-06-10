<?php

/**
 * Template for Help tab panel
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
        <div class="kepler_theme-portlet">
            <div class="kepler_theme-portlet-col f-g-3">
                <div class="an-end-faq">
                    <div>
                        <h2><?php esc_html_e('Troubleshoot','kepler_theme')?></h2>
                        <p class=""></p>
                    </div>
                </div>
            </div>
            <div class="kepler_theme-portlet-col f-g-7  an-align-center an-align-end">
                <table class="kepler_theme-td-list kepler_theme-FAQ">
                    <tr>
                        <td><a target="_blank" href="https://help.kepler.app/knowledgebase/troubleshoot-kepler-builder-experience/" class="faq-name"><?php esc_html_e("The Builder isn't working properly, what should I do?","kepler_theme")?></a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="kepler_theme-portlet">
        <div class="kepler_theme-portlet-col f-g-3">
            <div class="an-end-faq">
                <div>
                    <h2><?php esc_html_e("FAQ's","kepler_theme")?></h2>
                    <p class=""><?php esc_html_e("A regularly curated list of the most commonly asked questions by Kepler users.","kepler_theme")?></p>
                </div>
                <div>
                <?php esc_html_e("Couldn't find what you were looking for? Visit our","kepler_theme")?><a target="_blank" href="https://help.kepler.app/" class="faq-name"><?php esc_html_e(" Community.","kepler_theme")?></a>
                </div>
            </div>
        </div>

        <div class="kepler_theme-portlet-col f-g-7  an-align-center an-align-end">
            <table class="kepler_theme-td-list kepler_theme-FAQ">
                <tr>
                    <td><a target="_blank" href="https://help.kepler.app/knowledgebase/licenses/" class="faq-name"><?php esc_html_e("How many sites can I use Kepler Builder on?","kepler_theme")?></a></td>
                </tr>
                <tr>
                    <td><a target="_blank" href="https://help.kepler.app/knowledgebase_category/how-tos-tutorials/" class="faq-name"><?php esc_html_e("Do you'll have tutorials?","kepler_theme")?></a></td>
                </tr>
                <tr>
                    <td><a target="_blank" href="https://help.kepler.app/knowledgebase/whats-in-the-box/" class="faq-name"><?php esc_html_e("Does Kepler Builder work with any WordPress theme?","kepler_theme")?></a></td>
                </tr>
                <tr>
                    <td><a target="_blank" href="https://help.kepler.app/knowledgebase/create-pages-and-posts/" class="faq-name"><?php esc_html_e("Can I use Kepler Builder with Posts and Custom Posts?","kepler_theme")?></a></td>
                </tr>
                <tr>
                    <td><a target="_blank" href="https://help.kepler.app/knowledgebase/shortcode/" class="faq-name"><?php esc_html_e("Can I insert 3rd party shortcodes into Kepler Website Builder?","kepler_theme")?></a></td>
                </tr>
                <tr>
                    <td><a target="_blank" href="https://help.kepler.app/knowledgebase/support-terms/" class="faq-name"><?php esc_html_e("Do you have official Support?","kepler_theme")?></a></td>
                </tr>
                <tr>
                    <td><a target="_blank" href="https://help.kepler.app/feature-request/" class="faq-name"><?php esc_html_e("There's a feature that I need to build the site, how can I get it?","kepler_theme")?></a></td>
                </tr>
            </table>
        </div>
    </div>
</div>

</div>
<?php if ($showModal != '1') { ?>
<div class="an-modal-center">
    <div class="setup-container welcome-panel ">
        <h1><?php esc_html_e("Congratulations! You have successfully installed Kepler Builder","kepler_theme")?></h1>
        <p class="about-description"><?php esc_html_e("Let's get started with kepler builder. To start building your dream site click gets started below.","kepler_theme")?></p>
    </div>
    <div class="an-setup-footer-container">
        <div class="an-setup-footer">
            <div class="an-setup-button migrate">
                <a target="_blank" href="<?php echo get_site_url() . '?kepler_builder=1#welcome' ?>" id="kepler_theme__btn-install" class="button button-primary button-hero btn-install dashicons-before dashicons-migrate"><?php esc_html_e("Get Started with Kepler Builder","kepler_theme")?></a>
            </div>
        </div>
    </div>
</div>
<?php } ?>