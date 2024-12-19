<?php
/**
 * Already functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Already
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function already_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Already, use a find and replace
		* to change 'already' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'already', get_template_directory() . '/languages' );

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
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'already' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'already_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'already_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function already_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'already_content_width', 640 );
}
add_action( 'after_setup_theme', 'already_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function already_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'already' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'already' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'already_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'already_scripts' );
function already_scripts() {
	// wp_enqueue_style( 'already-style', get_stylesheet_uri(), array(), _S_VERSION );
    wp_enqueue_style('styles', get_template_directory_uri() . '/assets/css/styles.css');

	wp_style_add_data( 'already-style', 'rtl', 'replace' );

	wp_enqueue_script( 'already-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
    wp_enqueue_script( 'scripts', get_template_directory_uri() . '/assets/js/scripts.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


// Синхронизация JSON
if( function_exists('acf_add_local_field_group') ) {
    add_filter('acf/settings/load_json', function($paths) {
        $paths[] = get_template_directory() . '/acf-json';
        return $paths;
    });
}

// Регистрация кастомного поста "Movies"
function create_movies_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Movies',
            'singular_name' => 'Movie',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Movie',
            'edit_item' => 'Edit Movie',
            'new_item' => 'New Movie',
            'view_item' => 'View Movie',
            'search_items' => 'Search Movies',
            'not_found' => 'No movies found',
            'not_found_in_trash' => 'No movies found in Trash',
            'all_items' => 'All Movies',
            'menu_name' => 'Movies',
            'name_admin_bar' => 'Movie'
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'movies'),
        'show_in_rest' => true, 
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'taxonomies' => array('category'), 
        'menu_icon' => 'dashicons-format-video', 
    );

    register_post_type('movies', $args);
}
add_action('init', 'create_movies_post_type');

function add_movie_metaboxes() {
    add_meta_box('movie_release_date', 'Release date', 'movie_release_date', 'movies', 'side', 'default');
    add_meta_box('movie_rating', 'Rating', 'movie_rating', 'movies', 'side', 'default');
    add_meta_box('movie_year', 'Release year', 'movie_year', 'movies', 'side', 'default');
}
add_action('add_meta_boxes', 'add_movie_metaboxes');

function movie_release_date($post) {
    $release_date = get_post_meta($post->ID, '_movie_release_date', true);
    echo '<input type="date" name="movie_release_date" value="' . esc_attr($release_date) . '" />';
}

function movie_rating($post) {
    $rating = get_post_meta($post->ID, '_movie_rating', true);
    echo '<input type="text" name="movie_rating" value="' . esc_attr($rating) . '" />';
}

function movie_year($post) {
    $year = get_post_meta($post->ID, '_movie_year', true);
    echo '<input type="number" name="movie_year" value="' . esc_attr($year) . '" />';
}

function save_movie_metaboxes($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (isset($_POST['movie_release_date'])) {
        update_post_meta($post_id, '_movie_release_date', sanitize_text_field($_POST['movie_release_date']));
    }
    if (isset($_POST['movie_rating'])) {
        update_post_meta($post_id, '_movie_rating', sanitize_text_field($_POST['movie_rating']));
    }
    if (isset($_POST['movie_year'])) {
        update_post_meta($post_id, '_movie_year', sanitize_text_field($_POST['movie_year']));
    }

    return $post_id;
}
add_action('save_post', 'save_movie_metaboxes');

