<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package kepler_theme
 */

if ( ! function_exists( 'kepler_theme_meta' ) ) :
/**
 * Prints HTML with meta information for the current category and author.
 */
function kepler_theme_meta() {
	$post_category = '';
	$categories_list = get_the_category();
	$numItems = count($categories_list);
	$i = 0;
	foreach($categories_list as $category){
		$link = esc_url( get_category_link( $category->term_id ) );
		$comma = (++$i === $numItems) ? '' : ', ';
		$post_category .= sprintf(
			'<a href="'.$link.'">'.$category->name.'</a>' . $comma
		);
		
	}
	echo '<div class="posted-on"><div class="post-category"><span class="hint-text">'.esc_html__('Filed under','kepler_theme').' </span>' . $post_category . '</div></div>'; // WPCS: XSS OK.
}
endif;

if ( ! function_exists( 'kepler_theme_post_grid_title' ) ) :
/**
 * Prints HTML Title for Grid.
 */
function kepler_theme_post_grid_title($extraClass = null, $show_all_text) {
	echo '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
	$limiter = 75;
	if($extraClass):
		$limiter = 90;
	endif;	
	if($show_all_text):
		$limiter = 100;
	endif;
	echo mb_strimwidth(get_the_title(), 0, $limiter, '...');
	echo '</a></h1>';
}
endif;
if ( ! function_exists( 'kepler_theme_get_avatar' ) ) :
	/**
	 * Prints Author Avatar.
	 */
	function kepler_theme_get_avatar() {
		echo '<div class="article-author-thumbnail img-thumbnail">'.get_avatar( get_the_author_meta( 'ID' ), 64 ).'</div>';
	}
endif;

if ( ! function_exists( 'kepler_theme_author_info' ) ) :
	/**
	 * Prints Author info.
	 */
	function kepler_theme_author_info($hide_profile_pic) {
		$avatar_string = '';
		$details_string = '';
		
		if ( 'post' === get_post_type() ) {
			if(!$hide_profile_pic){
				$avatar_string = '<div class="img-thumbnail">'.get_avatar( get_the_author_meta( 'ID' ), 64 ).'</div>';
			}
			if(get_the_author_meta('first_name')){
				$authorname = get_the_author_meta('first_name').' '.get_the_author_meta('last_name');
			}
			else{
				$authorname = get_the_author_meta('nickname');
			}
			$bio = "";
			if(get_the_author_meta('description')){
				$bio = '<p>'.esc_html(get_the_author_meta('description')).'</p>';
			} else {
				$bio .= '<p>@'.esc_html(get_the_author_meta('nickname')).' This is a sample bio. You can change it from WordPress Dashboard, Users &#8594; Biographical Info.
				Biographical Info</p>';
			}
			
			$details_string = '<div class="author-info-wrapper"><div class="author-name"><a href="'.esc_url(get_author_posts_url(get_the_author_meta( 'ID' ))).'">'.esc_html($authorname).'</a></div><div class="author-meta">'.$bio.'</div></div>';
		}
		echo '<div class="author-wrapper">'.$avatar_string.$details_string.'</div>'; // WPCS: XSS OK.
		echo '<div class="article-info">';
		echo '<p class=""><span class="meta">'.esc_html__('Published Date :','kepler_theme').'</span>'.esc_html(get_the_date()).'</p> ';
		echo '<p class=""><span class="meta">'.esc_html__('Reading Time :','kepler_theme').'</span><span id="readTime" data-reading-time></span></p>';
		echo '</div>';
	
	}
	endif;

if ( ! function_exists( 'kepler_theme_author_grid' ) ) :
	/**
	 * Prints Author info in grid with category info
	 */
	function kepler_theme_author_grid() {
		$avatar_string = '';
		$details_string = '';

		//Get Categories
		$post_category = '';
		$categories_list = get_the_category();
		$numItems = count($categories_list);
		$i = 0;
		foreach($categories_list as $category){
			$link = esc_url( get_category_link( $category->term_id ) );
			$comma = (++$i === $numItems) ? '' : ', ';
			$post_category .= sprintf(
				'<a href="'.$link.'">'.$category->name.'</a>' . $comma
			);
			
		}

		if ( 'post' === get_post_type() ) {
			$avatar_string = '<div class="img-thumbnail">'.get_avatar( get_the_author_meta( 'ID' ), 32 ).'</div>';
			if(get_the_author_meta('first_name')){
				$authorname = get_the_author_meta('first_name').' '.get_the_author_meta('last_name');
			}
			else{
				$authorname = get_the_author_meta('nickname');
			}

			$details_string = '<div class="author-info-wrapper"><div class="author-name"><a href="'.esc_url(get_author_posts_url(get_the_author_meta( 'ID' ))).'">'.esc_html($authorname).'</a> <span class="hint-text">'.__("in ","kepler_theme").$post_category.'</span></div></div>';
		}

		echo '<div class="author-wrapper">'.$avatar_string.$details_string.'</div>'; // WPCS: XSS OK.
	
	}
endif;

if ( ! function_exists( 'kepler_theme_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function kepler_theme_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html__( 'Posted on %s', 'kepler_theme' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html__( 'by %s', 'kepler_theme' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'kepler_theme_limit_content' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function kepler_theme_limit_content($content) {
	// Take the existing content and return a subset of it
	return wp_trim_words($content, 40,'...' );

}
endif;

if ( ! function_exists( 'kepler_theme_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function kepler_theme_entry_footer() {
	// Hide category and tag text for pages.
	echo '<div class="post-utils">';
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ',', 'kepler_theme' ) );
		if ( $categories_list && kepler_theme_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'kepler_theme' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ' ', 'kepler_theme' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( '%1$s', 'kepler_theme' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'kepler_theme' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}

	echo '</div>';

}
endif;

if ( ! function_exists( 'kepler_theme_post_navigation' ) ) :
	/**
	 * Custom HTML to Print Post Navigation
	 * @link https://codex.wordpress.org/Next_and_Previous_Links
	 */
	function kepler_theme_post_navigation() {
		echo '<div class="navigation-post">';
		/* next link: wrap with div */
		next_post_link('%link','<span class="next-link navigation-link"><span class="next-link-text">Next</span></span>',true);
		/* previous link: wrap with div */
		previous_post_link( '%link','<span class="prev-link navigation-link"><span class="prev-link-text">Previous</span></span>',true );
		echo '</div>';
	}
endif;

if ( ! function_exists( 'kepler_theme_tags' ) ) :
	/**
	 * Prints HTML post tags
	 */
	function kepler_theme_tags() {
		// Hide category and tag text for pages.
		echo '<div class="post-utils">';
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__(' ', 'kepler_theme' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( '%1$s', 'kepler_theme' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}
		echo '</div>';
	}
	endif;


if ( ! function_exists( 'kepler_theme_post_social_share' ) ) :
	/**
	 * Display social media links
	 */
	function kepler_theme_post_social_share() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'kepler_theme' ) );
			if ( $categories_list && kepler_theme_categorized_blog() ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'kepler_theme' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}
	
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ' ', 'kepler_theme' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( '%1$s', 'kepler_theme' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}
	}
	endif;


if ( ! function_exists( 'kepler_theme_comment_form' ) ) :
/**
 * Prints Comments Forms with User Avatar.
 */
function kepler_theme_comment_form() {
	/* Logged in user */
	$user_identity = $aria_req = $commenter = $req = "";
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		// If Logout button needed :
		$avatar = '<div class="comment-form-wrapper"><div class="comment-figure"><div class="img-thumbnail">'.get_avatar( $current_user->ID, 40 ).'</div><span class="logged-in-as">'.$user_identity.'</span></div><textarea placeholder="Write a Response" class="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>';
	} else $avatar = '<p class="comment-form-comment"><label for="comment">' . __( 'Comment', 'kepler_theme' ) . '</label> <textarea  placeholder="Write a Response" class="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
	
	$comment_author = ($commenter != "") ? $commenter['comment_author'] : "";
	$comment_author_email = ($commenter != "") ? $commenter['comment_author_email'] : "";
	$comment_author_url = ($commenter != "") ? $commenter['comment_author_url'] : "";
	//Custom Template for non logged in users
	$customFields = array(
		'author' =>'<div class="fake-profile-wrapper comment-inline-form comment-inline-form-padding"><div class="img-thumbnail">'
			.'<img alt="Profile photo" src="http://2.gravatar.com/avatar/5485c6f87da25d43061dcb991aaaba1b?s=40&amp;d=mm&amp;r=g" class="avatar avatar-40 photo" height="40" width="40"></div><p class="comment-form-author form-control-group">' 
			.'<input class="author transparent" placeholder="Click here to enter your name" name="author" type="text" value="' .
			esc_attr( $comment_author ) . '" size="30"' . $aria_req . ' />'.
			
			( $req ? '<span class="required">*</span>' : '' )  .
			'</p></div>'
			,
		'email'  => '<div class="row comment-inline-form-padding"><p class="comment-form-email form-control-group col-12">' 
			. '<label for="email">' . __( 'Email Address', 'kepler_theme' ) . '</label> '
			. '<input id="email" placeholder="your-real-email@example.com" name="email" type="text" value="' . esc_attr( $comment_author_email  ) .
			'" size="30"' . $aria_req . ' />'  .
			( $req ? '<span class="required">*</span>' : '' ) 
			 .
			'</p>',
		'url'    => '<p class="comment-form-url form-control-group col-12 comment-inline-form-padding">' .
		 '<label for="url">' . __( 'Website', 'kepler_theme' ) . '</label>' .
		 '<input id="url" name="url" placeholder="http://your-site-name.com" type="text" value="' . esc_attr( $comment_author_url ) . '" size="30" /> ' .
		 '</p></div>'
	);

	$cancelIcon = kepler_theme_get_icon('kp_icon_close');
	comment_form(
		array(
			'title_reply'					=>	'',
			'cancel_reply_link' => $cancelIcon,
			'comment_notes_after' 	=> '',
			'comment_notes_before' 	=> '',
			'logged_in_as'				=> '',
			'class_submit' 				=> 'btn',
			'fields' => apply_filters('comment_form_default_fields', $customFields),
			'comment_field'       => $avatar,
		)
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function kepler_theme_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'kepler_theme_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'kepler_theme_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so kepler_theme_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so kepler_theme_categorized_blog should return false.
		return false;
	}
}

if( ! function_exists( 'kepler_theme_comment_block' ) ):
function kepler_theme_comment_block($comment, $args, $depth) {
    ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
    <div class="comment">
        <div class="img-thumbnail">
           <?php echo get_avatar($comment,$size='40',$default='https://storage.googleapis.com/kepler-marketing/default_avatar.png' ); ?>
        </div>
        <div class="comment-block">
            <div class="comment-arrow"></div>
                <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php esc_html_e('Your comment is awaiting moderation.','kepler_theme') ?></em>
                    <br />
                <?php endif; ?>
                <span class="comment-by">
                    <?php echo get_comment_author() ?>
                    <span class="float-right">
                        <span> <a href="#"><i class="fa fa-reply"></i> <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></a></span>
                    </span>
                </span>
				<?php comment_text() ?>
			<span class="date-time"><?php printf(/* translators: 1: date and time(s). */ esc_html__('%1$s at %2$s' , 'kepler_theme'), get_comment_date(),  get_comment_time()) ?></span>
		</div>
   </li>
<?php }endif;
/**
 * Flush out the transients used in kepler_theme_categorized_blog.
 */
function kepler_theme_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'kepler_theme_categories' );
}
/**
 * Prints HTML for comments disabled
 */
function kepler_theme_comments_disabled() {
	$disable_comment = '<div class="comments-disabled">';
	$disable_comment .= kepler_theme_get_icon('kp_icon_info');
	$disable_comment .= '<span class="">';
	$disable_comment .= esc_html__('Comments are disabled for this post', 'kepler_theme');
	$disable_comment .= '</span></div>';
	return $disable_comment;
}
add_action( 'edit_category', 'kepler_theme_category_transient_flusher' );
add_action( 'save_post',     'kepler_theme_category_transient_flusher' );


/**
 * Truncates content of a grid post and appends trailing ...
 */
function kepler_theme_get_truncated_body($contentLimit, $length) {
	//Limit Content from overflowing
	$words = wp_trim_words( get_the_content(), $contentLimit, ' ...' );
	$body = do_shortcode(mb_strimwidth($words, 0, $length, ' ...'));
	if(get_the_title() == "") {
		$body = '<a href="'.esc_url( get_permalink() ).'">'.$body.'</a>';
	}
	return $body;
 }

 /**
 * Get kepler_theme svg icons based on class name
 */
function kepler_theme_get_icon($icon_name) {
	//$icon_name = esc_html__($icon_name);
	$iconStyle = get_site_option("kepler_style_kit_icons");
    if($iconStyle == '') {
        $iconStyle = 'Rounded';
    }
	$url 	=  get_template_directory_uri();
	$icon   = '<div class="icon-wrapper icon-default kp_ '.$icon_name.'">';
	$icon 	.='<div class="multiLayered bgColor"></div><div class="multiLayered bgGradient"></div>';
	$icon	.='<svg class="kp_icon_svg" ><use xlink:href="'.$url.'/icons/'.$iconStyle.'/sprite.svg#'.$icon_name.'"></use></svg></div>';
	return $icon;
 }

  /**
 * Get kepler_theme hero-container
 */
function kepler_theme_get_hero($image,$showTooltip,$getColor,$extraClass) {
	
	if($showTooltip){
		$html = '<div class="hero-container '.$extraClass.'" data-init-plugin="article-hero-grid" data-article-tooltip="true" data-scroll-reveal="true">';
	}else{
		$html = '<div class="hero-container loading" data-init-plugin="article-hero" data-kepler="hero-parrallax">';
	}
	if($getColor){
		$html .= '<img src="'.esc_url($image).'" data-init-plugin="color-theif" alt="dummy-image">';
	}else{
		$html .= '<div class="reveal"></div>';
		$html .= '<img src="'.esc_url($image).'" alt="dummy-image">';
	}
	$html .= '<div class="real-image inner"></div>';
	$html .= '<div class="alt-space-tooltip">';
	$html .= __("Read","kepler_theme");
	$html .= '</div>';
	$html .= '</div><!-- .hero-container -->';
	return $html;
 }
 /**
 * Breadcrumbs for kepler_theme
 */
function kepler_theme_breadcrumb()
{
    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = kepler_theme_get_icon("kp_icon_chevron_right"); // delimiter between crumbs
    $home = 'Home'; // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb
	$allowed_html = array (
		'span' => array (
		'class' => array(),
		),
		'a' => array (
			'href' => array(),
			'class' => array(),
		),
	);
    global $post;
    $homeLink = home_url();
    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            echo '<div id="crumbs" class="breadcrumb"><a href="' . $homeLink . '">' . $home . '</a></div>';
        }
    } else {
        echo '<div id="crumbs" class="breadcrumb"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                echo get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
            }
            echo wp_kses($before,$allowed_html) . 'Archive by category "' . single_cat_title('', false) . '"' . wp_kses($after,$allowed_html);
        } elseif (is_search()) {
            echo wp_kses($before,$allowed_html) . 'Search results for "' . get_search_query() . '"' . wp_kses($after,$allowed_html);
        } elseif (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo wp_kses($before,$allowed_html) . get_the_time('d') . wp_kses($after,$allowed_html);
        } elseif (is_month()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo wp_kses($before,$allowed_html) . get_the_time('F') . wp_kses($after,$allowed_html);
        } elseif (is_year()) {
            echo wp_kses($before,$allowed_html) . get_the_time('Y') . wp_kses($after,$allowed_html);
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                if ($showCurrent == 1) {
                    echo ' ' . $delimiter . ' ' . wp_kses($before,$allowed_html) . get_the_title() . wp_kses($after,$allowed_html);
                }
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, true, ' ' . $delimiter . ' ');
                if ($showCurrent == 0) {
                    $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                }
                echo wp_kses($cats,$allowed_html);
                if ($showCurrent == 1) {
                    echo wp_kses($before,$allowed_html) . get_the_title() . wp_kses($after,$allowed_html);
                }
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo wp_kses($before,$allowed_html) . $post_type->labels->singular_name . wp_kses($after,$allowed_html);
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . wp_kses($before,$allowed_html) . get_the_title() . wp_kses($after,$allowed_html);
            }
        } elseif (is_page() && !$post->post_parent) {
            if ($showCurrent == 1) {
                echo wp_kses($before,$allowed_html) . get_the_title() . wp_kses($after,$allowed_html);
            }
        } elseif (is_page() && $post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo wp_kses($breadcrumbs[$i],$allowed_html);
                if ($i != count($breadcrumbs)-1) {
                    echo ' ' . $delimiter . ' ';
                }
            }
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . wp_kses($before,$allowed_html) . get_the_title() . wp_kses($after,$allowed_html);
            }
        } elseif (is_tag()) {
            echo wp_kses($before,$allowed_html) . 'Posts tagged "' . single_tag_title('', false) . '"' . wp_kses($after,$allowed_html);
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo wp_kses($before,$allowed_html) . 'Articles posted by ' . $userdata->display_name . wp_kses($after,$allowed_html);
        } elseif (is_404()) {
            echo wp_kses($before,$allowed_html) . 'Error 404' . wp_kses($after,$allowed_html);
        }
        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ' (';
            }
            echo __('Page','kepler_theme') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ')';
            }
        }
        echo '</div>';
    }
} // end the_breadcrumb()

/*
* Get 'Blog pages to show at most' value and round it 
* to the nearest multiple of 3 to fit the grid design
*/
function kepler_theme_get_posts_per_page() {
	$posts_per_page = (int)get_option('posts_per_page');

	if($posts_per_page == 3) {
		return 3;
	} else {
		return floor($posts_per_page/3.0) * 3;
	}
}

/*
* Get Read time for articles
*/
function kepler_theme_get_article_read_time() {
	$content = get_the_content();
	$word_count = str_word_count( strip_tags($content));
	$readTime = ceil($word_count/ 200);
	if($readTime == 0){
		$readTime = 1;
	}
	return $readTime;
}

/*
* Get List of authors in category
*/
function kepler_theme_list_author_in_this_cat ($with) {
	if (is_category()) {
		$current_category = get_query_var('cat');
		$args = array(
			'numberposts' => -1,
			'category' => $current_category,
			'orderby' => 'author',
			'order' => 'ASC'
		);
	} else {
		$tag_id = get_query_var('tag_id');
		$args = array(
			'numberposts' => -1,
			'tag__in' =>  $tag_id,
			'orderby' => 'author',
			'order' => 'ASC'
		);
	}
	$cat_posts = get_posts($args);

	$author_id_array = array();
	$user_posts = array();
	foreach( $cat_posts as $cat_post ):
		$user_posts[$cat_post->post_author][] = $cat_post->ID;
	endforeach;
	
	foreach( $user_posts as $key => $user_post ):   
		$user_post = array_unique($user_post);
		$count_user_posts[$key] = count($user_post);
	
		if ($count_user_posts[$key] >= $with) {
			$author_id_array[] = $key;
		}       
	endforeach;
	
	return $author_id_array; 
}