<?php
/**
 * Template part for displaying image posts
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
                <div class="media-preview" data-kepler_theme-parallax="true">
                    <div class="row justify-content-center">
                        <div class="col-12 column media-center-container">
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
                    <span><?php esc_html_e('Image by','kepler_theme'); ?></span>
                    <?php kepler_theme_author_info(false); ?>
                </div><!-- .author-details -->

                <div class="article-info">
                    <p class=""><span class="meta"><?php esc_html_e('Published date','kepler_theme'); ?> :</span>
                    <?php echo esc_html(get_the_date()) ?></p> 
                </div>

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