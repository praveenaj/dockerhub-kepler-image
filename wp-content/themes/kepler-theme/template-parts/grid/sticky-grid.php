<?php
/**
 * Template part for displaying sticky grid
 *
 * @package kepler_theme
 */

?>
<div class="container">
    <?php 
        if (has_post_thumbnail()) :
            $backgroundImg = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            //Hero container / Hero Image for post 
            echo kepler_theme_get_hero($backgroundImg[0],true,false,"");
        endif; 
    ?>
</div>

<div class="container main-container">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="grid-content sticky">
            <div class="row">
                <div class="col-lg-6 col-xl-8 col-md-12 column sticky-left">
                    <?php 
                        if (!has_post_thumbnail()) :
                            kepler_theme_post_grid_title(false,false);
                    endif; 
                    ?>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 column content-inner">
                    <div class="content">
                        <div class="read-time">
                            <?php 
                            //Show read time per article
                            echo kepler_theme_get_article_read_time() 
                            ?>
                            <?php esc_html_e('min read time','kepler_theme');?>
                        </div>
                        <?php if (has_post_thumbnail()) :
                            the_title('<h1 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h1>');
                        endif;
                        ?>
                        <?php
                        echo wp_trim_words(get_the_content(), 30, '...');
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'kepler_theme'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>
                    <div class="post-footer-author-wrapper">
                        <?php kepler_theme_author_grid(); ?>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>
