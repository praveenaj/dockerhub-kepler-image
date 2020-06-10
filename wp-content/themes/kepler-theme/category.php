<?php
/**
 * The template for displaying Category pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kepler_theme
 */

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

get_header(); ?>

<div id="primary" class="content-area">
    <div id="main" class="site-main" role="main">
        <?php
        if (have_posts()) : ?>
            <div class="container main-container">
                <div class="article-wrapper">
                    <div class="full-width no-margin">
                        <header class="post-filter-wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-xl-6 column">
                                    <p class="category-title"><?php esc_html_e('Category','kepler_theme');?></p>
                                    <h4> <?php single_cat_title(); ?></h4>
                                </div>
                                <div class="col-lg-6 col-xl-6 column author-list">
                                    <?php 

                                    $at_least = 1;  // at least 5 articles in current category or tags

                                    $author_array = kepler_theme_list_author_in_this_cat ($at_least);
                                    foreach (array_slice($author_array, 0, 4) as $author) : // limit 4 results?>
                                        <div class="img-thumbnail">
                                            <img alt="profile-avatar" src="<?php print get_avatar_url($author, ['size' => '40']); ?>" class="avatar avatar-40 photo" height="40" width="40">
                                        </div>
                                    <?php 
                                    endforeach;
                                    wp_reset_postdata(); 
                                    ?>
                                </div>
                            </div>
                        </header>
                        <div class="row">

                            <?php
                            /* Start the Loop */
                            while (have_posts()) : the_post(); ?>
                                <div class="col-lg-6 col-xl-4 grid-item">
                                    <?php

                                    /*
                                    * Include the Post-Format-specific template for the content.
                                    * If you want to override this in a child theme, then include a file
                                    * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                    */
                                    get_template_part('template-parts/grid/grid', get_post_format()); ?>
                                </div>
                            <?php
                        endwhile;
                        ?>
                        </div>
                        <?php 
                        $iconPrev = kepler_theme_get_icon('kp_icon_chevron_left');
                            $iconNext = kepler_theme_get_icon('kp_icon_chevron_right');

                            the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => $iconPrev,
                            'next_text' => $iconNext
                            ));
                    else :
                        get_template_part('template-parts/post/content', 'none');

                    endif; ?>

                </div>
            </div>
        </div>
        <?php
        get_footer(); ?>
    </div>
</div>