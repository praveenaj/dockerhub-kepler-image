<?php

/**
 * Template for default tab of Admin panel
 *
 * @since 1.0
 */

?>

<style>
    .an-modal-center {
        top: 0;
    }
</style>

<div class="an-modal-center">
    <div class="an-modal-inner">
        <div class="setup-container welcome-panel ">
            <h1>Hi there! 👋 Welcome to Kepler </h1>
            <p class="about-description">Great to have you with us! Before you start making your website. You need the following Kepler Builder compatible theme.</p>

            <div class="item-list-container">
                <div class="item-card">
                    <div class="an-item-thumb">
                        <img class="" src="https://storage.googleapis.com/kepler-marketing/hello-kepler-thumb.png" alt="Theme Screenshot" />
                    </div>
                    <div class="item-content">
                        <a target="_blank" href="https://kepler.app/free-wordpress-theme/" class="item-name"><?php echo esc_html('Hello Kepler Theme');?></a>
                        <p><?php echo esc_html('The most dynamic, user-friendly, multi-purpose WordPress theme. 
                        It is truly in a league of its own. Get an edge to your site by using Hello Kepler.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="an-setup-footer-container">
            <div class="an-setup-footer full-width">
            <p class="sm-text no-margin"> Hello Kepler Theme is Free, provided under the GNU General Public <a href="https://kepler.app/free-wordpress-theme/">License</a> version 3.</p>
                <div class="an-setup-button ">
                    <a href="https://storage.googleapis.com/kepler-download/kepler-theme.zip?ignoreCache=1" id="install" class="button button-primary button-hero btn-install">Download Now</a>
                </div>
            </div>
        </div>
    </div>

</div>