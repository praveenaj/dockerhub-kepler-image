<?php
/**
 * Template part for displaying post grid gallery
 *
 * @package kepler_theme
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="grid-content grid-video">
		<?php 
			$extraClass = "";
			if (has_post_thumbnail()):
				$backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				//Hero container / Hero Image for post 
				echo kepler_theme_get_hero($backgroundImg[0],true,false,"hero-dummy");
			else: //Different styles without featured image ?>
				<div class="hero-container hero-dummy" >
					<?php echo kepler_theme_get_icon('kp_icon_video'); ?>
				</div>
			<?php
				$extraClass = "";
			endif; ?>
		<div class="grid-content-inner <?php echo esc_attr($extraClass) ?>">
			<header class="title" data-init-plugin="clamp" data-line-height="0">
				<div class="title-inner" >
				<?php 
					kepler_theme_post_grid_title($extraClass,true);
				?>
				</div>
			</header>
			<div class="content"></div>
			<div class="post-footer-author-wrapper">
				<?php kepler_theme_author_grid(); ?>
			</div>	
		</div>
	</div>
</article>
