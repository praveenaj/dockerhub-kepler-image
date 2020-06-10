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
			//Limiter
			$contentLimit = 40;
			if (has_post_thumbnail()):
			$backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );	
		?>
		<div class="hero-container" style="background-image: url('<?php echo esc_url($backgroundImg[0]) ?>')"></div>
		<?php
		else:
			//Different styles without featured image
			$extraClass = "no-thumb";
			$contentLimit = 300;
		endif; ?>
		<div class="grid-content-inner <?php echo esc_attr($extraClass) ?>">
			<header class="title" data-init-plugin="clamp" data-line-height="0">
				<div class="title-inner">
                <h1 class="entry-title quote">
                    <a href="<?php echo esc_url( get_permalink() )?>" rel="bookmark">
                    <?php
                            if ( ! post_password_required() ) {
								echo kepler_theme_get_truncated_body($contentLimit, 75);
                            }
                            else{
                                //New Format for password protected text
                                echo "<p class='password-text'>The content is password protected.</p>";
                            }
                    ?>
                    </a>
                </h1>
				</div>
			</header>
			<div class="content">
				<div class="content-inner">
                <?php 
                    $limiter = 75;
                    if($extraClass):
                        $limiter = 55;
                    endif;	
                    echo mb_strimwidth(get_the_title(), 0, $limiter, '...');
				?>
				</div>
			</div>
			<div class="post-footer-author-wrapper">
				<?php kepler_theme_author_grid(); ?>
			</div>	
		</div>
	</div>
</article>
