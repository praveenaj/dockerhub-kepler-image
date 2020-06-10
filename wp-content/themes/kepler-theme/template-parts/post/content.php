<?php
/**
 * Template part for displaying posts
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
		//Hero container / Hero Image for post 
		echo kepler_theme_get_hero($backgroundImg[0],false,true,"");
		?>
	<?php
} ?>
	<div class="container main-container <?php echo esc_html($isFullWidth ? 'no-sidebar': '') ?>">
		<div class="article-wrapper loading">
			<div class="article-inner ">
				<div class="currently-reading" data-init-plugin="current-article">
					<div class="container entry-width">
						<div class="reading-shadow"> </div>
						<div class="reading-article-info">
							<div class="reading-caption"><?php esc_html_e("Now Reading","kepler_theme") ?></div>
							<?php the_title('<div class="reading-title">', '</div>'); ?>
						</div>
						<div class="reading-options">
							<div class="alt-space-tooltip">Copy link</div>
							<input type="text" id="postURL" value="<?php the_permalink() ?>"/>
							<a href="#" data-init="copy"> <?php echo kepler_theme_get_icon("kp_icon_link")?> </a>
							<a href="#" data-init="scoll-comments"><?php echo kepler_theme_get_icon("kp_icon_message_cirlce")?> <span> <?php echo get_comments_number() ?> </span> </a>
						</div>
						<div class="perc-wrapper">
							<div class="perc" id="perc"></div>
						</div>
					</div>

				</div><!-- counter -->
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
						<div class="article-details-wrapper hide-title-author">
							<?php kepler_theme_author_info(false); ?>
						</div>

					</div>
					
				</header><!-- .entry-header -->
				<div class="row justify-center">
					<div class="entry-wrapper entry-width">
						<div class="entry-content" >

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
							<span> Share this article </span>
							<div class="social-share-icons"> 
								<a target="_blank" href="https://www.facebook.com/sharer?u=<?php echo urlencode(get_permalink()); ?>&t=<?php echo urlencode(get_the_title()); ?>">
									<?php echo kepler_theme_get_icon('kp_icon_facebook'); ?> 
								</a>
								<a target="_blank" href="http://twitter.com/share?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>">
									<?php echo kepler_theme_get_icon('kp_icon_twitter'); ?> 
								</a> 
							</div>
							
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
				<?php 
				if (get_post_format() != 'image') {
					//the_post_navigation();
					kepler_theme_post_navigation();
				}
				?>
				<div class="related-post-wrapper">
					<h4><?php esc_attr_e('You might also like', 'kepler_theme');?></h4>
					<div class="container-fluid">
						<div class="row">
							<?php
							$related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 2, 'post__not_in' => array($post->ID) ) );
							if( $related ) foreach( $related as $post ) {
							setup_postdata($post); ?>
							
								<div class="col-lg-6 col-md-6 col-xl-6 grid-item">
								<?php
									
									/*
									* Include the Post-Format-specific template for the content.
									* If you want to override this in a child theme, then include a file
									* called content-___.php (where ___ is the Post Format name) and that will be used instead.
									*/
									get_template_part( 'template-parts/grid/grid', get_post_format() );?>
								</div>
							
							<?php }
							wp_reset_postdata(); 
						?>
						</div>
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