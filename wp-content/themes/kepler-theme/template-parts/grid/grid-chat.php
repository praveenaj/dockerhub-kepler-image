<?php
/**
 * Template part for displaying post grid chat
 *
 * @package kepler_theme
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="grid-content">

		<div class="grid-content-inner">
			<div class="content">
				<div class="chat-preview-wrapper">
					<div class="chat-preview">
							<?php
								the_content();
							?>
					</div>
				</div>
			</div>
			<header class="title">
				<?php 
				$extraClass = "no-thumb";
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
