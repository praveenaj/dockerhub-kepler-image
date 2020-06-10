<?php
/**
 * Template part for displaying post grid gallery
 *
 * @package kepler_theme
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="grid-content grid-gallery">
        <div class="hero-container hero-dummy">
				<?php 
                    echo kepler_theme_get_icon('kp_icon_image_gallery'); 
				?>
		</div>
		<div class="grid-content-inner">
			<header class="title">
				<?php
				    $extraClass = "";
					kepler_theme_post_grid_title($extraClass,false);
				?>
			</header>
			<div class="content"></div>
			<div class="post-footer-author-wrapper">
				<?php kepler_theme_author_grid(); ?>
			</div>	
		</div>
	</div>
</article>
