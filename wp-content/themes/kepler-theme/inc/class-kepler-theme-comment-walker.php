<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}

/**
 * Customized comments section
 *
 * @since 1.0
 */

if ( ! class_exists( 'kepler_theme_walker_comment' ) ) :
	class kepler_theme_walker_comment extends Walker_Comment {
		var $tree_type = 'comment';
		var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

		// constructor – wrapper for the comments list
		function __construct() { ?>

			<section class="comments-list">

        <?php }
        
        // start_lvl – wrapper for child comments list
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			$GLOBALS['comment_depth'] = $depth + 2; ?>

			<section class="children comments-list">

		<?php }

		// end_lvl – closing wrapper for child comments list
		function end_lvl( &$output, $depth = 0, $args = array() ) {
			$GLOBALS['comment_depth'] = $depth + 2; ?>

			</section>

		<?php }
		// start_el – HTML for comment template
		function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
			$depth++;
			$GLOBALS['comment_depth'] = $depth;
			$GLOBALS['comment'] = $comment;
			$parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' );

			if ( 'article' == $args['style'] ) {
				$tag = 'article';
				$add_below = 'comment';
			} else {
				$tag = 'article';
				$add_below = 'comment';
			} ?>

			<article <?php comment_class(empty( $args['has_children'] ) ? '' :'parent') ?> id="comment-<?php comment_ID() ?>" itemscope itemtype="http://schema.org/Comment">
				<div class="comment-inner" itemprop="text">
				<div class="comment-head">
					<div class="img-thumbnail"><?php echo get_avatar( $comment, 29 ); ?></div>
					<div class="comment-meta">
							<div class="comment-by">
								<?php comment_author(); ?>
							</div>
							<time class="date-time" title="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP') ?>" datetime="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP') ?>" itemprop="datePublished"><span><?php comment_date() ?></span></time>
					</div>
					<?php comment_reply_link(array_merge( $args, array('reply_text' => '<span>'.kepler_theme_get_icon("kp_icon_reply").'</span>', 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
					<div class="comment-block" role="complementary">
						<div class="comment-body">
							<?php if ($comment->comment_approved == '0') : ?>
							<p class="comment-meta-item">Your comment is awaiting moderation.</p>
							<?php endif; ?>
							<?php comment_text() ?>
						</div>
						<?php edit_comment_link('<p class="comment-meta-item">' . esc_html__('','kepler_theme') . '</p>','',''); ?>
					</div>
				</div>

		<?php }

		// end_el – closing HTML for comment template
		function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>

			</article>

		<?php }

		// destructor – closing wrapper for the comments list
		function __destruct() { ?>

			</section>

		<?php }

	}
endif;
?>