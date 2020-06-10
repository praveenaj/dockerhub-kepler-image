<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kepler_theme
 */

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
	return;
}
?>

<div class="comments-area">

    <?php if (!is_user_logged_in()) : ?>
    <div class="no-login-comment-form">
        <?php endif; ?>
        <?php kepler_theme_comment_form(); ?>
        <?php if (!is_user_logged_in()) : ?></div>
    <?php endif; ?>

    <?php if (get_comments_number() > 0) : ?>
    <div class="reply-count">
		<?php echo kepler_theme_get_icon('kp_icon_message_cirlce'); ?>
        <strong><?php echo get_comments_number(); ?></strong><span>Responses</span>
    </div>
    <?php endif; ?>
    
    <?php
	if (have_comments()) : ?>
    <?php if (get_comment_pages_count() > 1 && get_option('page_comments') && get_previous_comments_link()) : ?>
    <nav class="comment-nav-above navigation comment-navigation" role="navigation">
        <div class="nav-links">
            <div class="nav-previous"><?php echo kepler_theme_get_icon('kp_icon_chevron_up'); ?><?php previous_comments_link(esc_html__('View older comments...', 'kepler_theme')); ?></div>
        </div>
    </nav>
    <?php endif; ?>

    <ol class="comment-list">
        <?php
		wp_list_comments(array(
			'walker'  => new kepler_theme_walker_comment(),
			'style'      => 'ul',
            'short_ping' => true,
            'type' => 'all'
		));
		?>
    </ol>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments') && get_next_comments_link()) : ?>
    <nav class="comment-nav-below navigation comment-navigation" role="navigation">
        <div class="nav-links">
            <div class="nav-next"><?php echo kepler_theme_get_icon('kp_icon_chevron_down'); ?><?php next_comments_link(esc_html__('View newer comments...', 'kepler_theme')); ?></div>
        </div>
    </nav>
    <?php
endif; // Check for comment navigation.
endif; // Check for have_comments().
?>
<?php 
//No Responses
if (get_comments_number() == 0) : ?>
<div class="reply-count">
    <?php echo kepler_theme_get_icon('kp_icon_message_cirlce'); ?>
    <strong><?php echo get_comments_number(); ?></strong><span>Responses</span>
</div>
<div class="no-responses">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 82 78">
    <path fill="#BEC0C2" d="M19.8 30.5l-1.8-6c-.4-1.4-1.6-2.6-3-3l-6-1.8c-.7-.2-.7-1.2 0-1.4l6.1-1.8c1.4-.4 2.6-1.6 3-3l1.8-6c.2-.7 1.2-.7 1.4 0l1.8 6.1c.4 1.4 1.6 2.6 3 3l6 1.8c.7.2.7 1.2 0 1.4l-6.1 1.7c-1.4.4-2.6 1.6-3 3l-1.8 6c-.2.7-1.2.7-1.4 0zm13.3 41.1l.7-2.5c.3-.9.9-1.6 1.8-1.8l2.5-.7c.6-.2.6-1 0-1.2l-2.5-.7c-.9-.3-1.6-.9-1.8-1.8l-.7-2.5c-.2-.6-1-.6-1.2 0l-.7 2.5c-.3.9-.9 1.6-1.8 1.8l-2.5.7c-.6.2-.6 1 0 1.2l2.5.7c.9.3 1.6.9 1.8 1.8l.7 2.5c.2.6 1 .6 1.2 0zm34.1-32.8l.9-3c.3-1.1 1.1-1.9 2.2-2.2l3-.9c.7-.2.7-1.3 0-1.5l-3-.9c-1.1-.3-1.9-1.1-2.2-2.2l-.9-3c-.2-.7-1.3-.7-1.5 0l-.9 3c-.3 1.1-1.1 1.9-2.2 2.2l-3 .9c-.7.2-.7 1.3 0 1.5l3 .9c1.1.3 1.9 1.1 2.2 2.2l.9 3c.3.7 1.3.7 1.5 0zm-8.2 20.2l-1-1h-3l-1 1 1 1h3l1-1zm7 0l-1-1h-3l-1 1 1 1h3l1-1zm-5-2v-3l-1-1-1 1v3l1 1 1-1zm0 7v-3l-1-1-1 1v3l1 1 1-1zm-15-52l-1-1h-3l-1 1 1 1h3l1-1zm7 0l-1-1h-3l-1 1 1 1h3l1-1zm-5-2v-3l-1-1-1 1v3l1 1 1-1zm0 7v-3l-1-1-1 1v3l1 1 1-1zm-36 29l-1-1h-3l-1 1 1 1h3l1-1zm7 0l-1-1h-3l-1 1 1 1h3l1-1zm-5-2v-3l-1-1-1 1v3l1 1 1-1zm0 7v-3l-1-1-1 1v3l1 1 1-1z"></path>
</svg>
    <p>Seems a little quiet over here</p>
    <p class="hint-text">Be the first to comment on this post</p>
    <a href="#" data-kepler-trigger="comments">Write a response</a>
</div>
<?php endif; ?>
</div> 