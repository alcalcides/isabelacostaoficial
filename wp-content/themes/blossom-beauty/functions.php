<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * After setup theme hook
 */
function blossom_beauty_theme_setup(){
    /*
     * Make chile theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_child_theme_textdomain( 'blossom-beauty', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'blossom_beauty_theme_setup' );

function blossom_beauty_styles() {
    	$my_theme = wp_get_theme();
    	$version = $my_theme['Version'];
        
        wp_enqueue_style( 'blossom-feminine-style', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'animate' ) );
        
        wp_enqueue_style( 'blossom-beauty', get_stylesheet_directory_uri() . '/style.css', array( 'blossom-feminine-style' ), $version );
        
        wp_enqueue_script( 'blossom-beauty', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), $version, true );
        
        $array = array( 
            'rtl'       => is_rtl(),
            'animation' => get_theme_mod( 'slider_animation' ),
        ); 
        wp_localize_script( 'blossom-beauty', 'blossom_beauty_data', $array );
}
add_action( 'wp_enqueue_scripts', 'blossom_beauty_styles', 10 );

//Remove a function from the parent theme
function blossom_beauty_remove_parent_filters(){ 
    remove_action( 'customize_register', 'blossom_feminine_customizer_theme_info' );
    remove_action( 'customize_register', 'blossom_feminine_customize_register_color' );
    remove_action( 'customize_register', 'blossom_feminine_customize_register_appearance' );
}
add_action( 'init', 'blossom_beauty_remove_parent_filters' );

/** Body Classes */
function blossom_feminine_body_classes( $classes ) {

    $blog_layout_option = get_theme_mod( 'blog_layout_option', 'home-two' );
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
    
    if( $blog_layout_option == 'home-two' ){
        $classes[] = 'blog-layout-two';
    }

    return $classes;
}

function blossom_beauty_customizer_register( $wp_customize ) {

    $wp_customize->add_section( 'theme_info', array(
        'title'       => __( 'Demo & Documentation' , 'blossom-beauty' ),
        'priority'    => 6,
    ) );
    
    /** Important Links */
    $wp_customize->add_setting( 'theme_info_theme',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $theme_info = '<p>';
    $theme_info .= sprintf( __( 'Demo Link: %1$sClick here.%2$s', 'blossom-beauty' ),  '<a href="' . esc_url( 'https://demo.blossomthemes.com/blossom-beauty/' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p><p>';
    $theme_info .= sprintf( __( 'Documentation Link: %1$sClick here.%2$s', 'blossom-beauty' ),  '<a href="' . esc_url( 'https://blossomthemes.com/blossom-beauty-free-theme-documentation/' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p>';

    $wp_customize->add_control( new Blossom_Feminine_Note_Control( $wp_customize,
        'theme_info_theme', 
            array(
                'section'     => 'theme_info',
                'description' => $theme_info
            )
        )
    );

    /** Appearance Settings */
    $wp_customize->add_panel( 
        'appearance_settings',
         array(
            'priority'    => 50,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Appearance Settings', 'blossom-beauty' ),
            'description' => __( 'Customize Typography, Header Image & Background Image', 'blossom-beauty' ),
        ) 
    );
    
    /** Typography */
    $wp_customize->add_section(
        'typography_settings',
        array(
            'title'    => __( 'Typography', 'blossom-beauty' ),
            'priority' => 10,
            'panel'    => 'appearance_settings',
        )
    );
    
    /** Primary Font */
    $wp_customize->add_setting(
        'primary_font',
        array(
            'default'           => 'Muli',
            'sanitize_callback' => 'blossom_feminine_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Feminine_Select_Control(
            $wp_customize,
            'primary_font',
            array(
                'label'       => __( 'Primary Font', 'blossom-beauty' ),
                'description' => __( 'Primary font of the site.', 'blossom-beauty' ),
                'section'     => 'typography_settings',
                'choices'     => blossom_feminine_get_all_fonts(),  
            )
        )
    );
    
    /** Secondary Font */
    $wp_customize->add_setting(
        'secondary_font',
        array(
            'default'           => 'EB Garamond',
            'sanitize_callback' => 'blossom_feminine_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Feminine_Select_Control(
            $wp_customize,
            'secondary_font',
            array(
                'label'       => __( 'Secondary Font', 'blossom-beauty' ),
                'description' => __( 'Secondary font of the site.', 'blossom-beauty' ),
                'section'     => 'typography_settings',
                'choices'     => blossom_feminine_get_all_fonts(),  
            )
        )
    );
    
    /** Font Size*/
    $wp_customize->add_setting( 
        'font_size', 
        array(
            'default'           => 18,
            'sanitize_callback' => 'blossom_feminine_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Feminine_Slider_Control( 
            $wp_customize,
            'font_size',
            array(
                'section'     => 'typography_settings',
                'label'       => __( 'Font Size', 'blossom-beauty' ),
                'description' => __( 'Change the font size of your site.', 'blossom-beauty' ),
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 50,
                    'step'  => 1,
                )                 
            )
        )
    );
    
    /** Move Header Image section to appearance panel */
    $wp_customize->get_section( 'header_image' )->panel    = 'appearance_settings';
    $wp_customize->get_section( 'header_image' )->priority = 20;
    $wp_customize->remove_control( 'header_textcolor' );
    
    /** Move Background Image section to appearance panel */
    $wp_customize->get_section( 'background_image' )->panel    = 'appearance_settings';
    $wp_customize->get_section( 'background_image' )->priority = 30;

    /** Primary Color*/
    $wp_customize->add_setting( 
        'primary_color', array(
            'default'           => '#d8bbb5',
            'sanitize_callback' => 'sanitize_hex_color'
        ) 
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
            $wp_customize, 
            'primary_color', 
            array(
                'label'       => __( 'Primary Color', 'blossom-beauty' ),
                'description' => __( 'Primary color of the theme.', 'blossom-beauty' ),
                'section'     => 'colors',
                'priority'    => 5,                
            )
        )
    );

    /** Layout Settings */
    $wp_customize->add_panel(
        'layout_settings',
        array(
            'title'    => __( 'Layout Settings', 'blossom-beauty' ),
            'priority' => 55,
        )
    );

    /** Header Layout Section */
    $wp_customize->add_section(
        'header_layout',
        array(
            'title'    => __( 'Header Layout', 'blossom-beauty' ),
            'panel'    => 'layout_settings',
            'priority' => 10,
        )
    );
    
    /** Header layout */
    $wp_customize->add_setting( 
        'header_layout_option', 
        array(
            'default'           => 'two',
            'sanitize_callback' => 'blossom_feminine_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Feminine_Radio_Image_Control(
            $wp_customize,
            'header_layout_option',
            array(
                'section'     => 'header_layout',
                'label'       => __( 'Header Layout', 'blossom-beauty' ),
                'description' => __( 'This is the layout for header.', 'blossom-beauty' ),
                'choices'     => array(                 
                    'one'   => get_stylesheet_directory_uri() . '/images/header/header-one.jpg',
                    'two'   => get_stylesheet_directory_uri() . '/images/header/header-two.jpg',
                )
            )
        )
    );

    /** Slider Layout Settings */
    $wp_customize->add_section(
        'slider_layout_settings',
        array(
            'title'    => __( 'Slider Layout', 'blossom-beauty' ),
            'priority' => 20,
            'panel'    => 'layout_settings',
        )
    );
    
    /** Slider layout */
    $wp_customize->add_setting( 
        'slider_layout', 
        array(
            'default'           => 'two',
            'sanitize_callback' => 'blossom_feminine_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Feminine_Radio_Image_Control(
            $wp_customize,
            'slider_layout',
            array(
                'section'     => 'slider_layout_settings',
                'label'       => __( 'Slider Layout', 'blossom-beauty' ),
                'description' => __( 'Choose the layout of the slider for your site.', 'blossom-beauty' ),
                'choices'     => array(
                    'one'   => get_stylesheet_directory_uri() . '/images/slider/one.jpg',
                    'two'   => get_stylesheet_directory_uri() . '/images/slider/two.jpg',
                )
            )
        )
    );

    /** Instagram Title */
    $wp_customize->add_setting(
        'instagram_title',
        array(
            'default'           => __( 'FOLLOW ALONG', 'blossom-beauty' ),
            'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control(
        'instagram_title',
        array(
            'label'       => __( 'Instagram Section Title', 'blossom-beauty' ),
            'section'     => 'instagram_settings',
        )
    );
    
}
add_action( 'customize_register', 'blossom_beauty_customizer_register', 40 );

/** Categories */
function blossom_feminine_categories() {
    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list( esc_html__( ' ', 'blossom-beauty' ) );
        if ( $categories_list ) {
            echo '<span class="cat-links" itemprop="about">' . $categories_list . '</span>';
        }
    }       
}

/** Header */
function blossom_feminine_header(){ 
	$bg = get_header_image() ? ' style="background-image:url(' . esc_url( get_header_image() ) . ')"' : '';
	$header_layout = get_theme_mod( 'header_layout_option', 'two' ); ?>
	<header id="masthead" class="site-header wow fadeIn header-layout-<?php echo esc_attr( $header_layout ); ?>" data-wow-delay="0.1s" itemscope itemtype="http://schema.org/WPHeader">
		<div class="header-holder">
			<div class="header-t">
				<div class="container">			
					<div id="secondary-toggle-button"><i class="fa fa-bars"></i></div>              
					<nav id="secondary-navigation" class="secondary-nav" itemscope itemtype="http://schema.org/SiteNavigationElement">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'secondary',
							'menu_id'        => 'secondary-menu',
							'fallback_cb'    => 'blossom_feminine_secondary_menu_fallback',
						) );
						?>
					</nav><!-- #secondary-navigation -->             
					<div class="right">
						<div class="tools">
							<?php if( $header_layout == 'one') : ?>
								<div class="form-section">
									<span id="btn-search">
										<i class="fas fa-search"></i>
									</span>
									<div class="form-holder">
										<?php get_search_form(); ?>
									</div>
								</div>
								<?php if( blossom_feminine_is_woocommerce_activated() ) blossom_feminine_wc_cart_count(); 
							endif;
							?>   
						</div>
						<?php blossom_feminine_social_links(); ?>
                        <?php require 'searchCustomized.php' ?>
					</div>            
				</div>
			</div><!-- .header-t -->
		</div>
		<div class="header-m<?php if( $header_layout == 'one' ) echo " site-branding"; ?>" <?php echo $bg; ?>>
			<div class="container" itemscope itemtype="http://schema.org/Organization">
				<?php 
				if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
					the_custom_logo();
				} 
				if( is_front_page() ){ ?>
					<h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
					<?php 
				}else{ ?>
					<p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
					<?php
				}
				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ){ ?>
					<p class="site-description" itemprop="description"><?php echo $description; ?></p>
				<?php }	?>
			</div>
		</div><!-- .header-m -->

		<!-- Alcides extraiu o que se encontra no arquivo 'retirado.php'. Trata-se de um menu indesejado não editável pelo editor Wordpress. Para voltar, descomente o seguinte código php-->
        <?php //require 'retirado.php'; ?>
	</header><!-- #masthead -->
	<?php
}

/** Slider Layout */
function blossom_feminine_banner(){
    
    $ed_slider = get_theme_mod( 'ed_slider', true );
    $slider_layout  = get_theme_mod( 'slider_layout', 'two' );

    if( ( is_front_page() || is_home() ) && $ed_slider ){ 
        $slider_type    = get_theme_mod( 'slider_type', 'latest_posts' );
        $slider_cat     = get_theme_mod( 'slider_cat' );
        $posts_per_page = get_theme_mod( 'no_of_slides', 3 );
    
        $args = array(
            'post_type'           => 'post',
            'post_status'         => 'publish',            
            'ignore_sticky_posts' => true
        );
        
        if( $slider_type === 'cat' && $slider_cat ){
            $args['cat']            = $slider_cat; 
            $args['posts_per_page'] = -1;  
        }else{
            $args['posts_per_page'] = $posts_per_page;
        }
                
        $qry = new WP_Query( $args );
        
        if( $qry->have_posts() ){ ?>
            <div class="banner banner-layout-<?php echo esc_attr( $slider_layout ); ?>" data-wow-delay="0.1s">
                <div id="banner-slider" class="owl-carousel slider-layout-<?php echo esc_attr( $slider_layout ); ?>">
                    <?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                    <div class="item">
                        <?php 
                        if( has_post_thumbnail() ){
                            the_post_thumbnail( 'blossom-feminine-slider' );    
                        }else{ ?>
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/images/no-img_1920_649.png' ); ?>" alt="<?php the_title_attribute(); ?>" />
                            <?php 
                        }
                        ?>                    
                        <div class="banner-text">
                            <?php
                                blossom_feminine_categories();
                                the_title( '<h2 class="title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
                            ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        <?php
        wp_reset_postdata();
        }
    
    }
}

/** Entry Footer */
function blossom_feminine_entry_footer(){ ?>
    <footer class="entry-footer">
    <?php 
        if( is_home() ){ ?>
        	<a href="<?php the_permalink(); ?>" class="btn-readmore">
        		<svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 30 10">
        			<g id="arrow" transform="translate(-10)">
        				<path id="Path_16" data-name="Path 16" d="M24.5,44.974H46.613L44.866,40.5a34.908,34.908,0,0,0,9.634,5,34.908,34.908,0,0,0-9.634,5l1.746-4.474H24.5Z" transform="translate(-14.5 -40.5)"/>
        			</g>
        		</svg>
        	</a>
                <?php 
            
            /**
             * Social sharing in home page
            */
            do_action( 'blossom_feminine_social_sharing' );            
        } 
        //Tags in single page
        if( is_single() ) blossom_feminine_tags();
        //edit post link
        blossom_feminine_edit_post_link(); 
    ?>
	</footer><!-- .entry-footer home-->
    <?php
}

/** Comment */
function blossom_feminine_comment_count() {
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15">
		<path id="comment" data-name="comment" d="M15.5,2H3.5A1.5,1.5,0,0,0,2,3.5V17l3-3H15.5A1.5,1.5,0,0,0,17,12.5v-9A1.5,1.5,0,0,0,15.5,2Zm0,10.5H5L3.5,14V3.5h12Z" transform="translate(-2 -2)"/>
		</svg>';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'blossom-beauty' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}
}
function blossom_feminine_content_start(){
    
    $class = is_404() ? 'error-holder' : 'row' ; ?>
    <div class="featured-area">
        <div class="container">
            <?php 
            /**
             * Page Header
             * 
             * @hooked blossom_feminine_featured_section
            */
            do_action( 'blossom_feminine_featured_section' );
            ?>
        </div>
    </div>
    
    <div class="container main-content">
        
        <div id="content" class="site-content">
            <div class="<?php echo esc_attr( $class ); ?>">
    <?php
}
/** Content End */
function blossom_feminine_content_end(){ ?>
            </div><!-- .row/not-found -->
        </div><!-- #content -->
    </div><!-- .container/.main-content -->
    <?php
}

/** Instagram Gallery */
function blossom_feminine_instagram_gallery(){
    if( blossom_feminine_is_btif_activated() ){
        $ed_instagram = get_theme_mod( 'ed_instagram', false );
        $instagram_title = get_theme_mod( 'instagram_title', 'FOLLOW ALONG' );
        if( $ed_instagram ){
            echo '<div class="content-instagram">';
            echo '<span class="insta-title">' . esc_html( $instagram_title ) . '</span>';
            echo do_shortcode( '[blossomthemes_instagram_feed]' );
            echo '</div>';    
        }
    }
}
/** Newsletter section */
function blossom_beauty_newsletter(){
    if( blossom_feminine_is_btnw_activated()  && ! is_single() ){
        $ed_newsletter = get_theme_mod( 'ed_newsletter', false );
        $newsletter = get_theme_mod( 'newsletter_shortcode' ); ?>
        <div class="newsletter-section">
            <?php 
                if( $ed_newsletter && has_shortcode( $newsletter, 'BTEN' ) ){
                    echo '<div class="content-newsletter">';
                    echo do_shortcode( $newsletter );   
                    echo '</div>';            
                }
            ?>
        </div>
    <?php
    }
}
add_action( 'blossom_feminine_footer', 'blossom_beauty_newsletter', 20 );

/** Typography */
function blossom_feminine_fonts_url(){
    $fonts_url = '';
    
    $primary_font       = get_theme_mod( 'primary_font', 'Muli' );
    $ig_primary_font    = blossom_feminine_is_google_font( $primary_font );    
    $secondary_font     = get_theme_mod( 'secondary_font', 'EB Garamond' );
    $ig_secondary_font  = blossom_feminine_is_google_font( $secondary_font );    
    $site_title_font    = get_theme_mod( 'site_title_font', array( 'font-family'=>'Playfair Display', 'variant'=>'700italic' ) );
    $ig_site_title_font = blossom_feminine_is_google_font( $site_title_font['font-family'] );
        
    /* Translators: If there are characters in your language that are not
    * supported by respective fonts, translate this to 'off'. Do not translate
    * into your own language.
    */
    $primary    = _x( 'on', 'Primary Font: on or off', 'blossom-beauty' );
    $secondary  = _x( 'on', 'Secondary Font: on or off', 'blossom-beauty' );
    $site_title = _x( 'on', 'Site Title Font: on or off', 'blossom-beauty' );
    
    
    if ( 'off' !== $primary || 'off' !== $secondary || 'off' !== $site_title ) {
        
        $font_families = array();
     
        if ( 'off' !== $primary && $ig_primary_font ) {
            $primary_variant = blossom_feminine_check_varient( $primary_font, 'regular', true );
            if( $primary_variant ){
                $primary_var = ':' . $primary_variant;
            }else{
                $primary_var = '';    
            }            
            $font_families[] = $primary_font . $primary_var;
        }
         
        if ( 'off' !== $secondary && $ig_secondary_font ) {
            $secondary_variant = blossom_feminine_check_varient( $secondary_font, 'regular', true );
            if( $secondary_variant ){
                $secondary_var = ':' . $secondary_variant;    
            }else{
                $secondary_var = '';
            }
            $font_families[] = $secondary_font . $secondary_var;
        }
        
        if ( 'off' !== $site_title && $ig_site_title_font ) {
            
            if( ! empty( $site_title_font['variant'] ) ){
                $site_title_var = ':' . blossom_feminine_check_varient( $site_title_font['font-family'], $site_title_font['variant'] );    
            }else{
                $site_title_var = '';
            }
            $font_families[] = $site_title_font['font-family'] . $site_title_var;
        }
        
        $font_families = array_diff( array_unique( $font_families ), array('') );
        
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),            
        );
        
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }
     
    return esc_url_raw( $fonts_url );
}

/** Dyanmic CSS */
function blossom_feminine_dynamic_css(){
    
    $primary_font    = get_theme_mod( 'primary_font', 'Muli' );
    $primary_fonts   = blossom_feminine_get_fonts( $primary_font, 'regular' );
    $secondary_font  = get_theme_mod( 'secondary_font', 'EB Garamond' );
    $secondary_fonts = blossom_feminine_get_fonts( $secondary_font, 'regular' );
    $font_size       = get_theme_mod( 'font_size', 18 );
    
    $site_title_font      = get_theme_mod( 'site_title_font', array( 'font-family'=>'Playfair Display', 'variant'=>'700italic' ) );
    $site_title_fonts     = blossom_feminine_get_fonts( $site_title_font['font-family'], $site_title_font['variant'] );
    $site_title_font_size = get_theme_mod( 'site_title_font_size', 60 );
    
    $primary_color = get_theme_mod( 'primary_color', '#d8bbb5' );
    
    $rgb = blossom_feminine_hex2rgb( blossom_feminine_sanitize_hex_color( $primary_color ) );
     
    echo "<style type='text/css' media='all'>"; ?>
     
    .content-newsletter .blossomthemes-email-newsletter-wrapper.bg-img:after,
    .widget_blossomthemes_email_newsletter_widget .blossomthemes-email-newsletter-wrapper:after{
        <?php echo 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.8);'; ?>
    }
    
    /* primary color */
    a{
        color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
    }
    
    a:hover,
    a:focus{
        color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
    }

    .secondary-nav ul li a:hover,
    .secondary-nav ul li a:focus,
    .secondary-nav ul li:hover > a,
    .secondary-nav ul li:focus > a,
    .secondary-nav .current_page_item > a,
    .secondary-nav .current-menu-item > a,
    .secondary-nav .current_page_ancestor > a,
    .secondary-nav .current-menu-ancestor > a,
    .header-t .social-networks li a:hover,
    .header-t .social-networks li a:focus,
    .main-navigation ul li a:hover,
    .main-navigation ul li a:focus,
    .main-navigation ul li:hover > a,
    .main-navigation ul li:focus > a,
    .main-navigation .current_page_item > a,
    .main-navigation .current-menu-item > a,
    .main-navigation .current_page_ancestor > a,
    .main-navigation .current-menu-ancestor > a,
    .banner .banner-text .title a:hover,
    .banner .banner-text .title a:focus,
    #primary .post .text-holder .entry-header .entry-title a:hover,
    #primary .post .text-holder .entry-header .entry-title a:focus,
    .widget ul li a:hover,
    .widget ul li a:focus,
    .site-footer .widget ul li a:hover,
    .site-footer .widget ul li a:focus,
    #crumbs a:hover,
    #crumbs a:focus,
    .related-post .post .text-holder .cat-links a:hover,
    .related-post .post .text-holder .cat-links a:focus,
    .related-post .post .text-holder .entry-title a:hover,
    .related-post .post .text-holder .entry-title a:focus,
    .comments-area .comment-body .comment-metadata a:hover,
    .comments-area .comment-body .comment-metadata a:focus,
    .search #primary .search-post .text-holder .entry-header .entry-title a:hover,
    .search #primary .search-post .text-holder .entry-header .entry-title a:focus,
    .site-title a:hover,
    .site-title a:focus,
    .widget_bttk_popular_post ul li .entry-header .entry-meta a:hover,
    .widget_bttk_popular_post ul li .entry-header .entry-meta a:focus,
    .widget_bttk_pro_recent_post ul li .entry-header .entry-meta a:hover,
    .widget_bttk_pro_recent_post ul li .entry-header .entry-meta a:focus,
    .widget_bttk_posts_category_slider_widget .carousel-title .title a:hover,
    .widget_bttk_posts_category_slider_widget .carousel-title .title a:focus,
    .site-footer .widget_bttk_posts_category_slider_widget .carousel-title .title a:hover,
    .site-footer .widget_bttk_posts_category_slider_widget .carousel-title .title a:focus,
    .portfolio-sorting .button:hover,
    .portfolio-sorting .button:focus,
    .portfolio-sorting .button.is-checked,
    .portfolio-item .portfolio-img-title a:hover,
    .portfolio-item .portfolio-img-title a:focus,
    .portfolio-item .portfolio-cat a:hover,
    .portfolio-item .portfolio-cat a:focus,
    .entry-header .portfolio-cat a:hover,
    .entry-header .portfolio-cat a:focus,
    .header-layout-two .header-b .social-networks li a:hover, 
    .header-layout-two .header-b .social-networks li a:focus,
    #primary .post .text-holder .entry-header .entry-meta a:hover{
        color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
    }

    .pagination a:hover,
    .pagination a:focus,
    .widget_calendar caption,
    .widget_calendar table tbody td a,
    .widget_tag_cloud .tagcloud a:hover,
    .widget_tag_cloud .tagcloud a:focus,
    #blossom-top,
    .single #primary .post .entry-footer .tags a:hover,
    .single #primary .post .entry-footer .tags a:focus,
    .error-holder .page-content a:hover,
    .error-holder .page-content a:focus,
    .widget_bttk_author_bio .readmore:hover,
    .widget_bttk_author_bio .readmore:focus,
    .widget_bttk_social_links ul li a:hover,
    .widget_bttk_social_links ul li a:focus,
    .widget_bttk_image_text_widget ul li .btn-readmore:hover,
    .widget_bttk_image_text_widget ul li .btn-readmore:focus,
    .widget_bttk_custom_categories ul li a:hover .post-count,
    .widget_bttk_custom_categories ul li a:hover:focus .post-count,
    .content-instagram ul li .instagram-meta .like,
    .content-instagram ul li .instagram-meta .comment,
    #secondary .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:hover,
    #secondary .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:focus,
    #secondary .widget_blossomtheme_companion_cta_widget .btn-cta:hover,
    #secondary .widget_blossomtheme_companion_cta_widget .btn-cta:focus,
    #secondary .widget_bttk_icon_text_widget .text-holder .btn-readmore:hover,
    #secondary .widget_bttk_icon_text_widget .text-holder .btn-readmore:focus,
    .site-footer .widget_blossomtheme_companion_cta_widget .btn-cta:hover,
    .site-footer .widget_blossomtheme_companion_cta_widget .btn-cta:focus,
    .site-footer .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:hover,
    .site-footer .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:focus,
    .site-footer .widget_bttk_icon_text_widget .text-holder .btn-readmore:hover,
    .site-footer .widget_bttk_icon_text_widget .text-holder .btn-readmore:focus,
    .header-layout-two .header-b .tools .cart .count,
    #primary .post .text-holder .entry-header .cat-links a:hover,
    .widget_bttk_popular_post .style-two li .entry-header .cat-links a:hover, 
    .widget_bttk_pro_recent_post .style-two li .entry-header .cat-links a:hover, 
    .widget_bttk_popular_post .style-three li .entry-header .cat-links a:hover,
    .widget_bttk_pro_recent_post .style-three li .entry-header .cat-links a:hover, .widget_bttk_posts_category_slider_widget .carousel-title .cat-links a:hover,
    .widget_bttk_posts_category_slider_widget .owl-theme .owl-prev:hover, .widget_bttk_posts_category_slider_widget .owl-theme .owl-prev:focus, .widget_bttk_posts_category_slider_widget .owl-theme .owl-next:hover, .widget_bttk_posts_category_slider_widget .owl-theme .owl-next:focus,
    .banner .owl-nav .owl-prev:hover, 
    .banner .owl-nav .owl-next:hover,
    .banner .banner-text .cat-links a:hover,
    button:hover, input[type="button"]:hover, 
    input[type="reset"]:hover, input[type="submit"]:hover, 
    button:focus, input[type="button"]:focus, 
    input[type="reset"]:focus, 
    input[type="submit"]:focus,
    .category-section .col .img-holder:hover .text-holder span,
    .widget_bttk_author_bio .author-socicons li:hover,
    .owl-theme .owl-nav [class*=owl-]:hover,
    #primary .post .entry-content .highlight, 
    #primary .page .entry-content .highlight{
        background: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
    }

    .pagination .current,
    .post-navigation .nav-links .nav-previous a:hover,
    .post-navigation .nav-links .nav-next a:hover,
    .post-navigation .nav-links .nav-previous a:focus,
    .post-navigation .nav-links .nav-next a:focus,
    .content-newsletter .blossomthemes-email-newsletter-wrapper form input[type="submit"]:hover, .content-newsletter .blossomthemes-email-newsletter-wrapper form input[type="submit"]:focus{
        background: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
        border-color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
    }
    .content-newsletter .blossomthemes-email-newsletter-wrapper form input[type="submit"]:hover, .content-newsletter .blossomthemes-email-newsletter-wrapper form input[type="submit"]:focus{
        color: #fff;
    }

    #primary .post .entry-content blockquote,
    #primary .page .entry-content blockquote{
        border-bottom-color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
        border-top-color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
    }

    #primary .post .entry-content .pull-left,
    #primary .page .entry-content .pull-left,
    #primary .post .entry-content .pull-right,
    #primary .page .entry-content .pull-right{border-left-color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;}

    .error-holder .page-content h2{
        text-shadow: 6px 6px 0 <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
    }

    .category-section .col .img-holder:hover .text-holder,
    button:hover, input[type="button"]:hover, 
    input[type="reset"]:hover, 
    input[type="submit"]:hover, 
    button:focus, 
    input[type="button"]:focus, 
    input[type="reset"]:focus, 
    input[type="submit"]:focus{
        border-color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
    }

	#primary .post .text-holder .entry-footer .btn-readmore:hover svg path,
 	#primary .post .text-holder .entry-footer .btn-readmore:focus svg path{
 		fill: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
 	}
    .header-layout-two .header-t,
    #primary .post .text-holder .entry-header .cat-links a,
    .widget_bttk_popular_post .style-two li .entry-header .cat-links a, 
    .widget_bttk_pro_recent_post .style-two li .entry-header .cat-links a,
    .widget_bttk_popular_post .style-three li .entry-header .cat-links a, 
    .widget_bttk_pro_recent_post .style-three li .entry-header .cat-links a,
    .widget_bttk_popular_post .style-two li .entry-header .cat-links a, 
    .widget_bttk_pro_recent_post .style-two li .entry-header .cat-links a, 
    .widget_bttk_popular_post .style-three li .entry-header .cat-links a, 
    .widget_bttk_pro_recent_post .style-three li .entry-header .cat-links a, 
    .widget_bttk_posts_category_slider_widget .carousel-title .cat-links a,
    .content-instagram{
        <?php echo 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.3);'; ?>
    }
    .widget_bttk_author_bio .author-socicons li,
    .widget_bttk_author_bio .author-socicons li:last-child,
    .pagination a{
        <?php echo 'border-color: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.3);'; ?>
    }

    
    body,
    button,
    input,
    select,
    optgroup,
    textarea{
        font-family : <?php echo wp_kses_post( $primary_fonts['font'] ); ?>;
        font-size   : <?php echo absint( $font_size ); ?>px;
    }

    .widget_bttk_pro_recent_post ul li .entry-header .entry-title,
    .widget_bttk_posts_category_slider_widget .carousel-title .title,
    .widget_blossomthemes_email_newsletter_widget .blossomthemes-email-newsletter-wrapper .text-holder h3,
    #secondary .widget_bttk_testimonial_widget .text-holder .name,
    #secondary .widget_bttk_description_widget .text-holder .name,
    .site-footer .widget_bttk_description_widget .text-holder .name,
    .site-footer .widget_bttk_testimonial_widget .text-holder .name, 
    .widget_bttk_popular_post ul li .entry-header .entry-title, 
    .widget_bttk_author_bio .title-holder {
        font-family : <?php echo wp_kses_post( $primary_fonts['font'] ); ?>;
    }

    .banner .banner-text .title,
    #primary .sticky .text-holder .entry-header .entry-title,
    #primary .post .text-holder .entry-header .entry-title,
    .author-section .text-holder .title,
    .post-navigation .nav-links .nav-previous .post-title,
    .post-navigation .nav-links .nav-next .post-title,
    .related-post .post .text-holder .entry-title,
    .comments-area .comments-title,
    .comments-area .comment-body .fn,
    .comments-area .comment-reply-title,
    .page-header .page-title,
    #primary .post .entry-content blockquote,
    #primary .page .entry-content blockquote,
    #primary .post .entry-content .pull-left,
    #primary .page .entry-content .pull-left,
    #primary .post .entry-content .pull-right,
    #primary .page .entry-content .pull-right,
    #primary .post .entry-content h1,
    #primary .page .entry-content h1,
    #primary .post .entry-content h2,
    #primary .page .entry-content h2,
    #primary .post .entry-content h3,
    #primary .page .entry-content h3,
    #primary .post .entry-content h4,
    #primary .page .entry-content h4,
    #primary .post .entry-content h5,
    #primary .page .entry-content h5,
    #primary .post .entry-content h6,
    #primary .page .entry-content h6,
    .search #primary .search-post .text-holder .entry-header .entry-title,
    .error-holder .page-content h2,
    .portfolio-text-holder .portfolio-img-title,
    .portfolio-holder .entry-header .entry-title,
    .single-blossom-portfolio .post-navigation .nav-previous a,
    .single-blossom-portfolio .post-navigation .nav-next a,
    .related-portfolio-title,
    .content-instagram .insta-title,
    .content-newsletter .blossomthemes-email-newsletter-wrapper .text-holder h3{
        font-family: <?php echo  wp_kses_post( $secondary_fonts['font'] ); ?>;
    }

    .site-title{
        font-size   : <?php echo absint( $site_title_font_size ); ?>px;
        font-family : <?php echo wp_kses_post( $site_title_fonts['font'] ); ?>;
        font-weight : <?php echo wp_kses_post( $site_title_fonts['weight'] ); ?>;
        font-style  : <?php echo wp_kses_post( $site_title_fonts['style'] ); ?>;
    }
    
    <?php if( blossom_feminine_is_woocommerce_activated() ) { ?>
        .woocommerce ul.products li.product .add_to_cart_button:hover,
        .woocommerce ul.products li.product .add_to_cart_button:focus,
        .woocommerce ul.products li.product .product_type_external:hover,
        .woocommerce ul.products li.product .product_type_external:focus,
        .woocommerce nav.woocommerce-pagination ul li a:hover,
        .woocommerce nav.woocommerce-pagination ul li a:focus,
        .woocommerce #secondary .widget_shopping_cart .buttons .button:hover,
        .woocommerce #secondary .widget_shopping_cart .buttons .button:focus,
        .woocommerce #secondary .widget_price_filter .price_slider_amount .button:hover,
        .woocommerce #secondary .widget_price_filter .price_slider_amount .button:focus,
        .woocommerce #secondary .widget_price_filter .ui-slider .ui-slider-range,
        .woocommerce div.product form.cart .single_add_to_cart_button:hover,
        .woocommerce div.product form.cart .single_add_to_cart_button:focus,
        .woocommerce div.product .cart .single_add_to_cart_button.alt:hover,
        .woocommerce div.product .cart .single_add_to_cart_button.alt:focus,
        .woocommerce .woocommerce-message .button:hover,
        .woocommerce .woocommerce-message .button:focus,
        .woocommerce-cart #primary .page .entry-content .cart_totals .checkout-button:hover,
        .woocommerce-cart #primary .page .entry-content .cart_totals .checkout-button:focus,
        .woocommerce-checkout .woocommerce .woocommerce-info,
        .header-t .tools .cart .count{
            background: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
        }

        .woocommerce nav.woocommerce-pagination ul li a{
            border-color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
        }

        .woocommerce nav.woocommerce-pagination ul li span.current{
            background: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
            border-color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
        }

        .woocommerce div.product .entry-summary .product_meta .posted_in a:hover,
        .woocommerce div.product .entry-summary .product_meta .posted_in a:focus,
        .woocommerce div.product .entry-summary .product_meta .tagged_as a:hover,
        .woocommerce div.product .entry-summary .product_meta .tagged_as a:focus{
            color: <?php echo blossom_feminine_sanitize_hex_color( $primary_color ); ?>;
        }
            
    <?php } ?>
           
    <?php echo "</style>";
}

/** Footer */
function blossom_feminine_footer_bottom(){ ?>
    <div class="site-info">
        <div class="container">
            <?php
                blossom_feminine_get_footer_copyright();
                
                echo '<a href="' . esc_url( 'https://blossomthemes.com/themes/blossom-beauty-free-wordpress-theme' ) .'" rel="author" target="_blank">' . esc_html__( ' Blossom Beauty', 'blossom-beauty' ) . '</a>' . esc_html__( ' by Blossom Themes.', 'blossom-beauty' );
                
                printf( esc_html__( ' Powered by %s', 'blossom-beauty' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'blossom-beauty' ) ) .'" target="_blank">WordPress</a> .' );
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>                    
        </div>
    </div>
    <?php
}