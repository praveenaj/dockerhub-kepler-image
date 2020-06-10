<?php
/**
 * Template part for displaying video posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kepler_theme
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="media-viewer container" id="mediaViewer">
        <div class="media-container">
            <div class="media-preview-wrapper">
                <div class="media-preview">
                    <div class="row justify-content-center">
                        <div class="col-12 media-center-container">
                            <?php
                            the_content();
                            ?>
                        </div>
                    </div>

                </div>
            </div>
            <div class="media-content">
                <header class="">
                    <?php
                    if (is_single()) :
                        the_title('<h1 class="entry-title">', '</h1>');
                    else :
                        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                    endif;
                    ?>
                </header><!-- .entry-header -->
                <?php
                if ('post' === get_post_type()) :
                    kepler_theme_meta();
                endif;
                ?>
                <footer class="entry-footer">
                    <?php kepler_theme_entry_footer(); ?>
                </footer><!-- .entry-footer -->

                <div class="post-footer-author-wrapper">
                    <span><?php esc_html_e('Posted By','kepler_theme'); ?></span>
                    <?php kepler_theme_author_info(false); ?>
                </div><!-- .author-details -->

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                else :
                    echo kepler_theme_comments_disabled();
                endif;
                ?>
            </div>
        </div>

    </div>
</article><!-- #post-## -->