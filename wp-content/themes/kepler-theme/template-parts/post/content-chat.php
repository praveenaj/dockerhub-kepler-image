<?php
/**
 * Template part for displaying chat posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kepler_theme
 */

$isFullWidth = get_query_var('is_full_width');

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if (has_post_thumbnail()) {
		$backgroundImg = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
		?>
		<div class="hero-container" style="background-image: url('<?php echo esc_url($backgroundImg[0]) ?>')"></div>
	<?php
} ?>
	<div class="container main-container">
		<div class="article-wrapper">
			<div class="article-inner ">
				<header class="post-title-wrapper">
					<div class="post-title-inner">
						<div class="entry-title-wrapper">
							<div class="entry-title-inner">
								<?php
								if (is_single()) :
									the_title('<h1 class="entry-title">', '</h1>');
								else :
									the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
								endif;

								if ('post' === get_post_type()) :
									kepler_theme_meta();
								endif;
								?>
							</div>
						</div>
						<div class="<?php if(!$isFullWidth){ echo 'hide-title-author'; }?>">
						<?php kepler_theme_author_info(false); ?>
						</div>

					</div>
					
				</header><!-- .entry-header -->
				<div class="row justify-center">
					<div class="entry-wrapper entry-width">
						<div class="entry-content">
							<?php
							the_content(sprintf(
								/* translators: %s: Name of current post. */
								wp_kses(__('Continue reading %s <span class="meta-nav">&rarr;</span>', 'kepler_theme'), array('span' => array('class' => array()))),
								the_title('<span class="screen-reader-text">"', '"</span>', false)
							));

							wp_link_pages(array(
								'before' => '<div class="page-links">' . esc_html__('Pages:', 'kepler_theme'),
								'after'  => '</div>',
							));
							?>
						</div><!-- .entry-content -->
						<?php kepler_theme_tags(); ?>
						<div class="social-share-wrapper">
							<a target="_blank" href="https://www.facebook.com/sharer?u=<?php echo urlencode(get_permalink()); ?>&t=<?php echo urlencode(get_the_title()); ?>">
								<?php echo kepler_theme_get_icon('kp_icon_facebook'); ?> Share
							</a>
							<a target="_blank" href="http://twitter.com/share?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>">
								<?php echo kepler_theme_get_icon('kp_icon_twitter'); ?>
								Tweet
							</a>
						</div><!-- .social-share -->
						<div class="post-footer-author-wrapper">
							<span><?php esc_html_e('Written By','kepler_theme'); ?></span>
							<?php kepler_theme_author_info(false); ?>
						</div><!-- .author-details -->
						<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if (comments_open()) :
							comments_template();
						else :
							echo kepler_theme_comments_disabled();
						endif;
						?>
					</div>
				</div>

			</div>
			<?php
				//Get profile picture - avatar
				kepler_theme_get_avatar();
				if(!$isFullWidth):
					//With sidebar
					get_sidebar();
				else:
					//No sidebar only profile detail
			?>
				<aside class="widget-area">
					<?php kepler_theme_author_info(true); ?>
				</aside><!-- profile-sidebar -->
			<?php
				endif;
			?>
			<!-- .sidebar -->
		</div>
	</div>
</article><!-- #post-## -->