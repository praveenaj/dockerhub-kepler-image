<?php
/**
 * Template part for displaying post grid
 *
 * @package kepler_theme
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="grid-content">
		<?php 
			$extraClass = "";
			//Limiter
			$contentLimit = 40;
			if (has_post_thumbnail()):
			$backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );	
		?>
		<?php
			//Hero container / Hero Image for post 
			echo kepler_theme_get_hero($backgroundImg[0],true,false,"");
		?>
		<?php
		else:
			//Different styles without featured image
			$extraClass = "no-thumb";
			$contentLimit = 300;
		endif; ?>
		<div class="grid-content-inner <?php echo esc_attr($extraClass) ?>">
			<div class="read-time">
				<?php 
				//Show read time per article
				echo kepler_theme_get_article_read_time() 
				?>
				<?php esc_html_e('min read time','kepler_theme');?>
			</div>
			<header class="title" data-init-plugin="clamp" data-line-height="0">
				<div class="title-inner" >
				<?php 

				$format = get_post_format() ? : 'standard';

				if(post_password_required()) {
					echo kepler_theme_get_icon('kp_icon_lock'); 
					
				} else if( $format == 'aside') { 
					echo kepler_theme_get_icon('kp_icon_file_text'); 
				
				} else if( $format == 'link') {
					echo kepler_theme_get_icon('kp_icon_link'); 
				
				} else if( $format == 'image') {
					echo kepler_theme_get_icon('kp_icon_image'); 
				
				} else if( $format == 'quote') {
					echo kepler_theme_get_icon('kp_icon_lock'); 
				
				} else if( $format == 'video') {
					echo kepler_theme_get_icon('kp_icon_video'); 

				}
				?>
				<?php 
					if(get_the_content()){
						kepler_theme_post_grid_title($extraClass,false);
					}else{
						kepler_theme_post_grid_title($extraClass,true);
					}
				?>
				</div>
			</header>
			<div class="content">
				<?php
					$clamp = "";
					//JS clamp init for none password grid
					if ( ! post_password_required() ) {
						$clamp = 'data-init-plugin=clamp';
					}
				?>
				<div class="content-inner" <?php echo esc_html($clamp) ?> >
					<?php
						if ( ! post_password_required() ) {
							echo kepler_theme_get_truncated_body($contentLimit, 455);
						}
						else{
							//New Format for password protected text
							echo "<p class='password-text'>The content is password protected.</p>";
						}
					?>
				</div>
			</div>
			<div class="post-footer-author-wrapper">
				<?php kepler_theme_author_grid(); ?>
			</div>	
		</div>
	</div>
</article>