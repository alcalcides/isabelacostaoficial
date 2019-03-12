<?php
/**
 * Custom functions
 *
 * @package Blossom_Feminine
 */

if ( ! function_exists( 'blossom_feminine_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function blossom_feminine_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Blossom Feminine, use a find and replace
	 * to change 'blossom-feminine' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'blossom-feminine', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'   => esc_html__( 'Primary', 'blossom-feminine' ),
        'secondary' => esc_html__( 'Secondary', 'blossom-feminine' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'blossom_feminine_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array(
        'header-text' => array( 'site-title', 'site-description' ),
    ) );
    
    /**
     * Set up the WordPress core custom header feature.
     */
    add_theme_support( 'custom-header', apply_filters( 'blossom_feminine_custom_header_args', array(
		'default-image' => '',
        'width'         => 1920,
		'height'        => 180,
		'header-text'   => false
	) ) );
    
    /** Images sizes */
    add_image_size( 'blossom-feminine-slider', 1920, 649, true );
    add_image_size( 'blossom-feminine-featured', 1410, 600, true );
    add_image_size( 'blossom-feminine-with-sidebar', 1020, 600, true );
    add_image_size( 'blossom-feminine-blog', 435, 435, true );
    add_image_size( 'blossom-feminine-cat', 450, 300, true );
    add_image_size( 'blossom-feminine-related', 320, 200, true );
    add_image_size( 'blossom-feminine-schema', 600, 60 );

    
    // Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );
}
endif;
add_action( 'after_setup_theme', 'blossom_feminine_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function blossom_feminine_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'blossom_feminine_content_width', 1020 );
}
add_action( 'after_setup_theme', 'blossom_feminine_content_width', 0 );

if( ! function_exists( 'blossom_feminine_template_redirect_content_width' ) ) :
/**
* Adjust content_width value according to template.
*
* @return void
*/
function blossom_feminine_template_redirect_content_width(){
	// Full Width in the absence of sidebar.
	if( is_singular() ){
	   $sidebar_layout = blossom_feminine_sidebar_layout();
       if( ( $sidebar_layout == 'full-width' ) || ! is_active_sidebar( 'sidebar' ) ) $GLOBALS['content_width'] = 1410;        
	}elseif( ! is_active_sidebar( 'sidebar' ) ){
		$GLOBALS['content_width'] = 1410;
	}
}
endif;
add_action( 'template_redirect', 'blossom_feminine_template_redirect_content_width' );

/**
 * Enqueue scripts and styles.
 */
function blossom_feminine_scripts() {
	// Use minified libraries if SCRIPT_DEBUG is false
    $build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
    
    wp_enqueue_style( 'owl-carousel', get_template_directory_uri(). '/css' . $build . '/owl.carousel' . $suffix . '.css', array(), '2.2.1' );
    wp_enqueue_style( 'animate', get_template_directory_uri(). '/css' . $build . '/animate' . $suffix . '.css', array(), '3.5.2' );
    wp_enqueue_style( 'blossom-feminine-google-fonts', blossom_feminine_fonts_url(), array(), null );
    wp_enqueue_style( 'blossom-feminine-style', get_stylesheet_uri(), array(), BLOSSOM_FEMININE_THEME_VERSION );

    if( blossom_feminine_is_woocommerce_activated() )
    wp_enqueue_style( 'blossom-feminine-woocommerce-style', get_template_directory_uri(). '/css' . $build . '/woocommerce' . $suffix . '.css', array( 'blossom-feminine-style' ), BLOSSOM_FEMININE_THEME_VERSION );
    
    wp_enqueue_script( 'all', get_template_directory_uri() . '/js' . $build . '/all' . $suffix . '.js', array( 'jquery' ), '5.2.0', true );
    wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js' . $build . '/v4-shims' . $suffix . '.js', array( 'jquery' ), '5.2.0', true );
	wp_enqueue_script( 'sticky-kit', get_template_directory_uri() . '/js' . $build . '/sticky-kit' . $suffix . '.js', array(), '1.1.3', true );
    wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js' . $build . '/owl.carousel' . $suffix . '.js', array( 'jquery' ), '2.2.1', true );
	wp_enqueue_script( 'jquery-matchHeight', get_template_directory_uri() . '/js' . $build . '/jquery.matchHeight' . $suffix . '.js', array( 'jquery' ), '0.7.2', true );
    wp_enqueue_script( 'wow', get_template_directory_uri() . '/js' . $build . '/wow' . $suffix . '.js', array( 'jquery' ), '1.1.3', true );
    wp_enqueue_script( 'blossom-feminine-custom', get_template_directory_uri() . '/js' . $build . '/custom' . $suffix . '.js', array( 'jquery' ), BLOSSOM_FEMININE_THEME_VERSION, true );
    
    $array = array( 
        'rtl'       => is_rtl(),
        'animation' => get_theme_mod( 'slider_animation' ),
    ); 
    wp_localize_script( 'blossom-feminine-custom', 'blossom_feminine_data', $array );
    
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'blossom_feminine_scripts' );

if( ! function_exists( 'blossom_feminine_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function blossom_feminine_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image custom-background';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color custom-background';
	}
    
    if( is_search() && ! is_post_type_archive( 'product' ) ){
        $classes[] = 'search-result-page';   
    }
    
    $classes[] = blossom_feminine_sidebar_layout();
    
	return $classes;
}
endif;
add_filter( 'body_class', 'blossom_feminine_body_classes' );

if( ! function_exists( 'blossom_feminine_post_classes' ) ) :
/**
 * Add custom classes to the array of post classes.
*/
function blossom_feminine_post_classes( $classes ){
    
    if( is_home() ){
        $classes[] = 'wow fadeIn';
    }
    
    if( is_search() ){
        $classes[] = 'search-post';
    }
    
    return $classes;
}
endif;
add_filter( 'post_class', 'blossom_feminine_post_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function blossom_feminine_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'blossom_feminine_pingback_header' );

if ( ! function_exists( 'blossom_feminine_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function blossom_feminine_excerpt_more($more) {
	 return is_admin() ? $more : ' &hellip; ';
}

endif;
add_filter( 'excerpt_more', 'blossom_feminine_excerpt_more' );

if ( ! function_exists( 'blossom_feminine_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function blossom_feminine_excerpt_length( $length ) {
	$excerpt_length = get_theme_mod( 'excerpt_length', 55 );
    return is_admin() ? $length : absint( $excerpt_length );    
}
endif;
add_filter( 'excerpt_length', 'blossom_feminine_excerpt_length', 999 );

if( ! function_exists( 'blossom_feminine_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function blossom_feminine_change_comment_form_default_fields( $fields ){
    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );    
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'blossom-feminine' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr__( 'Name*', 'blossom-feminine' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'blossom-feminine' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr__( 'Email*', 'blossom-feminine' ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'blossom-feminine' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'blossom-feminine' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;
    
}
endif;
add_filter( 'comment_form_default_fields', 'blossom_feminine_change_comment_form_default_fields' );

if( ! function_exists( 'blossom_feminine_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function blossom_feminine_change_comment_form_defaults( $defaults ){
    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'blossom-feminine' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'blossom-feminine' ) . '" cols="45" rows="8" aria-required="true"></textarea></p>';
    
    return $defaults;
    
}
endif;
add_filter( 'comment_form_defaults', 'blossom_feminine_change_comment_form_defaults' );

if( ! function_exists( 'blossom_feminine_exclude_cat' ) ) :
/**
 * Exclude post with Category from blog and archive page. 
*/
function blossom_feminine_exclude_cat( $query ){
    $ed_slider      = get_theme_mod( 'ed_slider', true );
    $slider_type    = get_theme_mod( 'slider_type', 'latest_posts' );
    $slider_cat     = get_theme_mod( 'slider_cat' );
    $posts_per_page = get_theme_mod( 'no_of_slides', 3 );
    
    if( ! is_admin() && $query->is_main_query() && $query->is_home() && $ed_slider ){
        if( $slider_type === 'cat' && $slider_cat  ){            
 			$query->set( 'category__not_in', array( $slider_cat ) );    		
        }else{
            $args = array(
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'posts_per_page'      => $posts_per_page,
                'ignore_sticky_posts' => true
            );
            $latest = get_posts( $args );
            $excludes = array();
            foreach( $latest as $l ){
                array_push( $excludes, $l->ID );
            }
            $query->set( 'post__not_in', $excludes );
        }  
    }      
}
endif;
add_filter( 'pre_get_posts', 'blossom_feminine_exclude_cat' );

if( ! function_exists( 'blossom_feminine_get_the_archive_title' ) ) :
/**
 * Filter Archive Title
*/
function blossom_feminine_get_the_archive_title( $title ){
    
    $ed_prefix = get_theme_mod( 'ed_prefix_archive', false );
    
    if( is_post_type_archive( 'product' ) ){
        $title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
    }elseif( $ed_prefix ){
        if( is_category() ){
            $title = single_cat_title( '', false );
        }elseif ( is_tag() ){
            $title = single_tag_title( '', false );
        }elseif( is_author() ){
            $title = '<span class="vcard">' . get_the_author() . '</span>';
        }elseif ( is_year() ) {
            $title = get_the_date( __( 'Y', 'blossom-feminine' ) );
        }elseif ( is_month() ) {
            $title = get_the_date( __( 'F Y', 'blossom-feminine' ) );
        }elseif ( is_day() ) {
            $title = get_the_date( __( 'F j, Y', 'blossom-feminine' ) );
        }elseif ( is_post_type_archive() && ! is_post_type_archive( 'product' ) ) {
            $title = post_type_archive_title( '', false );       
        }elseif ( is_tax() ) {
            $tax = get_taxonomy( get_queried_object()->taxonomy );
            $title = single_term_title( '', false );
        }
    }    
    return $title;
}
endif;
add_filter( 'get_the_archive_title', 'blossom_feminine_get_the_archive_title' );

if( ! function_exists( 'blossom_feminine_remove_archive_description' ) ) :
    /**
     * filter the_archive_description & get_the_archive_description to show post type archive
     * @param  string $description original description
     * @return string post type description if on post type archive
     */
    function blossom_feminine_remove_archive_description( $description ){
        $shop_archive_description = get_theme_mod( 'shop_archive_description', true );
        if( is_post_type_archive( 'product' ) ) {
            if( ! $shop_archive_description ){
                $description = '';
            }
        }
        return wpautop( wp_kses_post( $description ) );
    }
endif;
add_filter( 'get_the_archive_description', 'blossom_feminine_remove_archive_description' );

if( ! function_exists( 'blossom_feminine_single_post_schema' ) ) :
/**
 * Single Post Schema
 *
 * @return string
 */
function blossom_feminine_single_post_schema() {
    if ( is_singular( 'post' ) ) {
        global $post;
        $custom_logo_id = get_theme_mod( 'custom_logo' );

        $site_logo   = wp_get_attachment_image_src( $custom_logo_id , 'blossom-feminine-schema' );
        $images      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $excerpt     = blossom_feminine_escape_text_tags( $post->post_excerpt );
        $content     = $excerpt === "" ? mb_substr( blossom_feminine_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
        $schema_type = ! empty( $custom_logo_id ) && has_post_thumbnail( $post->ID ) ? "BlogPosting" : "Blog";

        $args = array(
            "@context"  => "http://schema.org",
            "@type"     => $schema_type,
            "mainEntityOfPage" => array(
                "@type" => "WebPage",
                "@id"   => get_permalink( $post->ID )
            ),
            "headline"  => ( function_exists( '_wp_render_title_tag' ) ? wp_get_document_title() : wp_title( '', false, 'right' ) ),
            "image"     => array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            ),
            "datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
            "dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
            "author"        => array(
                "@type"     => "Person",
                "name"      => blossom_feminine_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
            ),
            "publisher" => array(
                "@type"       => "Organization",
                "name"        => get_bloginfo( 'name' ),
                "description" => get_bloginfo( 'description' ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            ),
            "description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
        );

        if ( has_post_thumbnail( $post->ID ) ) :
            $args['image'] = array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            );
        endif;

        if ( ! empty( $custom_logo_id ) ) :
            $args['publisher'] = array(
                "@type"       => "Organization",
                "name"        => get_bloginfo( 'name' ),
                "description" => get_bloginfo( 'description' ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            );
        endif;

        echo '<script type="application/ld+json">' , PHP_EOL;
        echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
        echo '</script>' , PHP_EOL;
    }
}
endif;
add_action( 'wp_head', 'blossom_feminine_single_post_schema' );

if( ! function_exists( 'blossom_feminine_get_comment_author_link' ) ) :
    /**
     * Filter to modify comment author link
     * @link https://developer.wordpress.org/reference/functions/get_comment_author_link/
     */
    function blossom_feminine_get_comment_author_link( $return, $author, $comment_ID ){
        $comment = get_comment( $comment_ID );
        $url     = get_comment_author_url( $comment );
        $author  = get_comment_author( $comment );
     
        if ( empty( $url ) || 'http://' == $url )
            $return = '<span itemprop="name">'. esc_html( $author ) .'</span>';
        else
            $return = "<span itemprop='name'><a href='$url' rel='external nofollow' class='url' itemprop='url'>$author</a></span>";

        return $return;
    }
endif;
add_filter( 'get_comment_author_link', 'blossom_feminine_get_comment_author_link', 10, 3 );

if( ! function_exists( 'blossom_feminine_redirect_on_activation' ) ) :
/**
 * Redirect to Getting Started page on theme activation
 *
 */
function blossom_feminine_redirect_on_activation() {
    global $pagenow;

    if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
        wp_redirect( admin_url( "themes.php?page=blossom-feminine-starter" ) );
    }
}
endif;
add_action( 'admin_init', 'blossom_feminine_redirect_on_activation' );