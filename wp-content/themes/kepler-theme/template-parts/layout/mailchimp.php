<?php
/**
 * Template part for displaying mailchimp newletter section
 * @package kepler_theme
 */

?>
<div class="newsletter-wrapper"> 
    <div class="newsletter-title">
        <h2><?php esc_html_e('Never miss a story. Be the first to know about our brand new posts.','kepler_theme');?></h2>
    </div>
    <div class="mc-newsletter-signup"> 
        <!-- Mailchip newsletter -->
        <?php if ( is_active_sidebar( 'kepler_theme-mailchimp-widget-area' ) ) : ?>
            <div id="newletter-widget-area" class="newletter-widget-area widget-area" role="complementary">
                <?php dynamic_sidebar( 'kepler_theme-mailchimp-widget-area' ); ?>
            </div><!-- #newletter-widget-area -->
        <?php else: ?>
        <!-- This is a sample form will be removed after widgets are added -->
            <div id="mc_embed_signup">
                <form action="#" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" novalidate>
                    <div id="mc_embed_signup_scroll">
                    <label for="">Subscribe</label>
                    <input type="email" value="" name="" class="email" placeholder="Enter your email address" required>
                    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" class="button"></div>
                    </div>
                </form>
            </div>
            <div class="newsletter-terms"><?php esc_html_e("By clicking sign up you'll receive occasional emails from kepler_theme. You always have the choice to adjust your interest settings or unsubscribe.","kepler_theme");?></div>
        <!-- End sample form -->
        <?php endif; ?>
    </div>
</div><!-- .newsletter-wrapper -->