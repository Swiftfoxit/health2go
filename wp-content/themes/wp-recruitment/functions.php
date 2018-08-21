<?php
/**
 * Theme Framework functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * @package CMSSuperHeroes
 * @subpackage Recruitment
 * @since Recruitment 1.0.9
 */

// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 625;
	
/**
 * CMS Theme setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * CMS Theme supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Recruitment 1.0.9
 */
function recruitment_setup() {

	// load language.
	load_theme_textdomain( 'wp-recruitment' , get_template_directory() . '/languages' );

	// Adds title tag
	add_theme_support( "title-tag" );
	
	// Add woocommerce
	add_theme_support('woocommerce');
	
	// Adds custom header
	add_theme_support( 'custom-header' );
	
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'video', 'audio' , 'gallery', 'quote') );


	register_nav_menu( 'primary', esc_html__( 'Primary Menu', 'wp-recruitment' ) );
	register_nav_menu( 'pr_menu_left', esc_html__( 'Main Menu Left', 'wp-recruitment' ) );
	register_nav_menu( 'pr_menu_right', esc_html__( 'Main Menu Right', 'wp-recruitment' ) );

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array('default-color' => 'e6e6e6',) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	add_image_size('recruitment_350X350', 350, 350, true);
	add_image_size('recruitment_blog_size1', 880, 370, true);
	add_image_size('recruitment_blog_size2', 680, 474, true);
	add_image_size('recruitment_blog_size3', 658, 221, true);
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
	
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
}

add_action( 'after_setup_theme', 'recruitment_setup' );

/**
 * Custom params & remove VC Elements.
 * 
 * @author FOX
 */

function recruitment_vc_after_init(){
    
    require_once ( get_template_directory() . '/vc_params/vc_custom_heading.php' );
    require_once ( get_template_directory() . '/vc_params/cms_custom_pagram_vc.php' );
    
    vc_remove_element( "vc_button" );
    vc_remove_element( "vc_button2" );
    vc_remove_element( "vc_cta_button" );
    vc_remove_element( "vc_cta_button2" );
    vc_remove_element( "vc_cta" );
    vc_remove_element( "cms_fancybox" );
    vc_remove_element( "cms_counter" );
}
add_action('vc_after_init', 'recruitment_vc_after_init');

/* Include CMS Shortcode */
function recruitment_shortcodes_list(){
	$recruitment_shortcodes_list = array(
		'cms_carousel',
		'cms_grid',
		'cms_fancybox_single',
		'cms_counter_single',
		'cms_progressbar',
		);

	return $recruitment_shortcodes_list;
}

function recruitment_get_post_meta($post_id = 0){
	global $post;
	if(!$post_id) $post_id = $post->ID;

	$_post_meta = maybe_unserialize(get_post_meta($post_id, 'opt_meta_options', true));

	if($_post_meta) return $_post_meta;

	return null;
}

/**
 * Add new elements for VC
 * 
 * @author FOX
 */

function recruitment_vc_elements(){
    
    if(class_exists('CmsShortCode')) {
    	add_filter('cms-shorcode-list', 'recruitment_shortcodes_list');

	    require_once( get_template_directory() . '/inc/elements/button/cms_button.php' );
	    require_once( get_template_directory() . '/inc/elements/googlemap/cms_googlemap.php' );
	    require_once( get_template_directory() . '/inc/elements/heading/cms_heading.php' );
	    require_once( get_template_directory() . '/inc/elements/process/cms_process.php' );
	    require_once( get_template_directory() . '/inc/elements/client/cms_client.php' );
	    require_once( get_template_directory() . '/inc/elements/pricing/cms_pricing.php' );
	    require_once( get_template_directory() . '/inc/elements/gettouch/cms_gettouch.php' );
	    require_once( get_template_directory() . '/inc/elements/jobboard/cms-specialism-list.php' );
	    require_once( get_template_directory() . '/inc/elements/jobboard/cms-jobs.php' );
	    require_once( get_template_directory() . '/inc/elements/jobboard/cms-jobs-grid.php' );
	    require_once( get_template_directory() . '/inc/elements/jobboard/cms_locations.php' );
	    require_once( get_template_directory() . '/inc/elements/jobboard/cms-cv.php' );
	    require_once( get_template_directory() . '/inc/elements/jobboard/cms-jb-resources.php' );
	    require_once( get_template_directory() . '/inc/elements/latestnews/cms_latestnews.php' );
	    require_once( get_template_directory() . '/inc/elements/newsletter/cms_newsletter.php' );
	    require_once( get_template_directory() . '/inc/elements/textbox/cms_textbox.php' );
	}
}
add_action('vc_before_init', 'recruitment_vc_elements');

/* Add widgets */
require_once( get_template_directory() . '/inc/widgets/textbox.php' );
require_once( get_template_directory() . '/inc/widgets/recent-posts.php' );

/* Job */
require_once( get_template_directory() . '/inc/jobboard/wpl-template-functions.php' );
require_once( get_template_directory() . '/inc/jobboard/wpl-template-hooks.php' );

/**
 * Enqueue scripts and styles for front-end.
 * @author CMSSuperHeroes Team
 * @since CMS SuperHeroes 1.0
 */
function recruitment_front_end_scripts() {
    
	global $wp_styles, $opt_meta_options;

	$theme = wp_get_theme(get_template());
	
	/* One Page */
	if(is_page() && isset($opt_meta_options['page_one_page']) && $opt_meta_options['page_one_page']) {
		wp_register_script('jquery.singlePageNav', get_template_directory_uri() . '/assets/js/jquery.singlePageNav.js', array('jquery'), '1.2.0');
		wp_localize_script('jquery.singlePageNav', 'one_page_options', array('filter' => '.is-one-page', 'speed' => $opt_meta_options['page_one_page_speed']));
		wp_enqueue_script('jquery.singlePageNav');
	}

	/*------------------------------------- JavaScript ---------------------------------------*/
	
	/** --------------------------libs--------------------------------- */

	/* jquery */
	wp_enqueue_script('jquery');
	
	/* Adds JavaScript Bootstrap. */
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), '3.3.2');

	/* Adds Magnific Popup. */
	wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array(), '1.0.0');

	/* Add main.js */
	wp_enqueue_script('wp-recruitment-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);
	
	/* Add menu.js */
	wp_enqueue_script('wp-recruitment-menu', get_template_directory_uri() . '/assets/js/menu.js', array(), '1.0.0', true);
    
	/* Comment */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Add Same Height */
    wp_enqueue_script('matchHeight', get_template_directory_uri() . '/assets/js/jquery.matchHeight-min.js', array( 'jquery' ), '1.0.0', true);

    /* Slick */
	wp_register_style('cms-slick-css', get_template_directory_uri() . '/assets/css/slick.css', array(), '1.0.1');
	wp_register_script('cms-slick-js', get_template_directory_uri() . '/assets/js/slick.min.js', array( 'jquery' ), '1.0.0', true);

	/* Style Scroll */
    wp_enqueue_script('scroll-bar', get_template_directory_uri() . '/assets/js/enscroll.js', array( 'jquery' ), '1.0.0', true);
	
	/** ----------------------------------------------------------------------------------- */
	
	/* Loads Bootstrap stylesheet. */
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '3.3.2');
	
	/* Loads Bootstrap stylesheet. */
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '4.3.0');

	/* Loads Magnific Popup stylesheet. */
	wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), '1.0.0');

	/* Font Material Icon */
	wp_enqueue_style('font-material-icon', get_template_directory_uri() . '/assets/css/material-design-iconic-font.min.css', array(), '4.3.0');
	
	/* Loads our main stylesheet. */
	wp_enqueue_style( 'wp-recruitment-style', get_stylesheet_uri(), array( 'bootstrap' ));

	/* Loads the Internet Explorer specific stylesheet. */
	wp_enqueue_style( 'wp-recruitment-ie', get_template_directory_uri() . '/assets/css/ie.css', array( 'wp-recruitment-style' ), '20121010' );
	
	/* ie */
	$wp_styles->add_data( 'wp-recruitment-ie', 'conditional', 'lt IE 9' );

	/* Loads Pe Icon. */
	wp_enqueue_style('cmssuperheroes-pe-icon', get_template_directory_uri() . '/assets/css/pe-icon-7-stroke.css', array(), '1.0.1');
	
	/* Load static css*/
	wp_enqueue_style('wp-recruitment-static', get_template_directory_uri() . '/assets/css/static.css', array( 'wp-recruitment-style' ), $theme->get('Version'));
}

add_action( 'wp_enqueue_scripts', 'recruitment_front_end_scripts' );

/**
 * load admin scripts.
 * 
 * @author FOX
 */
function recruitment_admin_scripts(){

	/* Loads Bootstrap stylesheet. */
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '4.3.0');

	$screen = get_current_screen();

	/* load js for edit post. */
	if($screen->post_type == 'post'){
		/* post format select. */
		wp_enqueue_script('post-format', get_template_directory_uri() . '/assets/js/post-format.js', array(), '1.0.0', true);
	}
}

add_action( 'admin_enqueue_scripts', 'recruitment_admin_scripts' );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Fox
 */
function recruitment_widgets_init() {

	global $opt_theme_options;

	register_sidebar( array(
		'name' => esc_html__( 'Main Sidebar', 'wp-recruitment' ),
		'id' => 'main-sidebar',
		'description' => esc_html__( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'wp-recruitment' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'JobBoard Profile & Basket (Logo Left)', 'wp-recruitment' ),
		'id' => 'nav',
		'description' => esc_html__( 'Only support widget (Basket, Profile, Shop Cart.)', 'wp-recruitment' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'JobBoard Profile & Basket (Logo Center)', 'wp-recruitment' ),
		'id' => 'nav-center',
		'description' => esc_html__( 'Only support widget (Basket, Profile, Shop Cart.)', 'wp-recruitment' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'JobBoard Jobs Listing Top', 'wp-recruitment' ),
		'id' => 'job3',
		'description' => esc_html__( 'Jobs sidebar Default for Archive', 'wp-recruitment' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'JobBoard Jobs Listing Middle', 'wp-recruitment' ),
		'id' => 'job2',
		'description' => esc_html__( 'Jobs sidebar Border for Archive', 'wp-recruitment' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '<i class="zmdi zmdi-caret-down"></i></h3><div class="wg-content">',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'JobBoard Jobs Listing Bottom', 'wp-recruitment' ),
		'id' => 'job',
		'description' => esc_html__( 'Jobs sidebar no Default for Archive', 'wp-recruitment' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'JobBoard Single Job', 'wp-recruitment' ),
		'id' => 'job-single',
		'description' => esc_html__( 'Single job sidebar.', 'wp-recruitment' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );

    register_sidebar( array(
        'name' => esc_html__( 'JobBoard Employers Listing', 'wp-recruitment' ),
        'id' => 'jobboard-sidebar-employers',
        'description' => esc_html__( 'Employers Listing sidebar for JobBoard', 'wp-recruitment' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="wg-title">',
        'after_title' => '<i class="zmdi zmdi-caret-down"></i></h3>',
    ) );

    register_sidebar( array(
        'name' => esc_html__( 'JobBoard Candidates Listing', 'wp-recruitment' ),
        'id' => 'jobboard-sidebar-candidates',
        'description' => esc_html__( 'Candidates Listing sidebar for JobBoard', 'wp-recruitment' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="wg-title">',
        'after_title' => '<i class="zmdi zmdi-caret-down"></i></h3>',
    ) );

    register_sidebar( array(
        'name' => esc_html__( 'JobBoard Dashboard', 'wp-recruitment' ),
        'id' => 'dashboard',
        'description' => esc_html__( 'Jobs sidebar for Dashboard', 'wp-recruitment' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="wg-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
		'name' => esc_html__( 'JobBoard Page', 'wp-recruitment' ),
		'id' => 'job-page',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );

	/* Footer Top */
	if(!empty($opt_theme_options['footer-top-column'])) {

		for($i = 1 ; $i <= $opt_theme_options['footer-top-column'] ; $i++){
			register_sidebar(array(
				'name' => sprintf(esc_html__('Footer Top - Column %s', 'wp-recruitment'), $i),
				'id' => 'sidebar-footer-top-' . $i,
				'description' => esc_html__('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'wp-recruitment'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="wg-title">',
				'after_title' => '</h3>',
			));
		}
	}

	/* Footer Bottom */
	register_sidebar( array(
        'name' => esc_html__( 'Footer Bottom Right', 'wp-recruitment' ),
        'id' => 'footer_bottom_right',
        'description' => esc_html__( 'Aplly footer layout 2', 'wp-recruitment' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="wg-title">',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'recruitment_widgets_init' );

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Recruitment 1.0.9
 */
function recruitment_page_menu_args( $args ) {
    if ( ! isset( $args['show_home'] ) )
        $args['show_home'] = true;
    return $args;
}

add_filter( 'wp_page_menu_args', 'recruitment_page_menu_args' );



/**
 * Display navigation to next/previous comments when applicable.
 *
 * @since Recruitment 1.0.9
 */
function recruitment_comment_nav() {
    // Are there comments to navigate through?
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
    ?>
	<nav class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'wp-recruitment' ); ?></h2>
		<div class="nav-links">
			<?php
				if ( $prev_link = get_previous_comments_link( esc_html__( 'Older Comments', 'wp-recruitment' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( esc_html__( 'Newer Comments', 'wp-recruitment' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
	<?php
	endif;
}

/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since Recruitment 1.0.9
 */
function recruitment_paging_nav() {
    // Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	// Set up paginated links.
	$links = paginate_links( array(
			'base'     => $pagenum_link,
			'total'    => $GLOBALS['wp_query']->max_num_pages,
			'current'  => $paged,
			'mid_size' => 1,
			'add_args' => array_map( 'urlencode', $query_args ),
			'prev_text' => 'Prev',
			'next_text' => 'Next',
	) );

	if ( $links ) :

	?>
	<nav class="navigation cms-paging-navigation clearfix" role="navigation">
			<div class="pagination loop-pagination">
				<?php echo wp_kses_post($links); ?>
			</div><!-- .pagination -->
	</nav><!-- .navigation -->
	<?php
	endif;
}

/* Add Custom Comment */
function recruitment_custom_list_comment($comment, $args, $depth) {
    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo esc_attr($tag); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
    <div class="comment-inner">
	    <div class="comment-author vcard">
	        <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	        <div class="comment-right">
	        	<cite class="fn"><?php printf( '%s' , get_comment_author_link() ); ?></cite>
		        <div class="comment-date commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"></a>
		    </div>
	    </div>
	    <?php if ( $comment->comment_approved == '0' ) : ?>
	         <em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'wp-recruitment' ); ?></em>
	          <br />
	    <?php endif; ?>

	</div>
	<div class="commetn-text">
    	<?php comment_text(); ?>
    	<div class="reply">
	        <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	    </div>
    </div>

    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
    <?php
}
/* Twiiter */
function recruitment_ago($time) {
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago ";
}

/* core functions. */
require_once( get_template_directory() . '/inc/functions.php' );


add_action( 'vc_before_init', 'recruitment_integrate_with_vc' );
function recruitment_integrate_with_vc() {
	vc_add_param("vc_tta_section", array(
			"type" => "css_editor",
			"param_name" => 'body_css',
			"group" => esc_html__("Style", 'wp-recruitment'),
		)
	);
}

/* Add Google font */
function recruitment_add_google_fonts() {
	wp_enqueue_style( 'poppins-google-font', 'https://fonts.googleapis.com/css?family=Poppins:400,400i,700,800', false );
}
add_action( 'wp_enqueue_scripts', 'recruitment_add_google_fonts' );

/* Update CSS within in Admin */
function recruitment_admin_style() {
  wp_enqueue_style('recruitment_admin_style', get_template_directory_uri().'/assets/css/admin.css');
}
add_action('admin_enqueue_scripts', 'recruitment_admin_style');