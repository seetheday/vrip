<?php
/**
 * Plugin Name.
 *
 * @package   Visual_Recipe_Index_Pro
 * @author    Simon Austin <simon@kremental.com>
 * @license   GPL-2.0+
 * @link      http://kremental.com
 * @copyright 2014 Kremental
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Visual_Recipe_Index_Pro
 * @author  Simon Austin <email@kremental.com>
 */
class Visual_Recipe_Index_Pro {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * @TODO - Rename "plugin-name" to the name of your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'visual-recipe-index-pro';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( '@TODO', array( $this, 'action_method_name' ) );
		add_filter( '@TODO', array( $this, 'filter_method_name' ) );
		

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();

					restore_current_blog();
				}

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

					restore_current_blog();

				}

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		add_action('init',array($this, 'options-init'));
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/* Define the variables used for styles here */
		
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/masonry.pkgd.min.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}
	
	/**
	 * Function to define the shortcode option 
	 * 
	 * @param array $atts	Contains shortcode information
	 * 
	 * @since 1.0.0
	 */
	public function vrip_shortcode_handler($atts){
		// Get shortcode values
		$a = shortcode_atts( array (
				'id' => 1,
				'num' => 2000,
				'orderby' => 'title',
				'theme' => 'masonry'
		), $atts );
		
		// Calls to functions that create the required output
		if ($a['theme'] == 'masonry') {
			return self::generate_masonry_template($a);
		} else {
			return "foo and bar";
		}
	}
	
	/**
	 * Function to get posts
	 * 
	 * @param array	$a	Array containing shortcode info
	 * 
	 * @since 1.0.0
	 * 
	 * @return	resource $posts	Contains WP posts for called category
	 * 
	 */
	private function vrip_get_posts($a){
		// First build the query array
		$vrip = array('cat' => $a['id'],
				'posts_per_page' => $a['num'],
				'orderby' => $a['orderby']
		);
		// Make the call to the native Wordpress function WP_Query
		$wpquery = new WP_Query($vrip);
		return $wpquery->posts;		
	}
	
	/**
	 * Function to create the masonry type recipe index
	 * 
	 * @since 1.0.0
	 */
	private function generate_masonry_template($a){
		// Get published posts for this category
		$posts = self::vrip_get_posts($a);
		$output = "<div id='vrip-container'>" . PHP_EOL;
		// Iterate through each post to get the right image and link
		foreach ($posts as $single){
			$output .= self::vrip_build_item($single);
		}
		$output .= "<div>" . PHP_EOL;  // Close out to vrip-container div
		return $output;
	}
	
	/**
	 * Function to create a single image/link combo
	 * 
	 * @param array $single
	 * 
	 * @return	string $output	Html to display for single post	
	 * 
	 * @since 1.0.0
	 */
	private function vrip_build_item($single){
		// Get the image source
		if(has_post_thumbnail($single->ID)){
			$image = wp_get_attachment_image_src(get_post_thumbnail_id($single->ID), 'single-post-thumbnail');
			$img = $image[0];
		} else {
			preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $single->post_content, $matches);
			// Ideally should have error check here, add next version
			// STUB
			$img = $matches[1][0];
		}
		
		if(empty($img)){
			// Put in a default image, ideally chosen by the user and available via a standard settings option in the DB
			// STUB
		}
		
		// Process the image using Wordpress native function to ensure image is small and sized correct
		// STUB 

		// Get the url
		$url = get_permalink($single->ID);
		
		// Get the text to display
		$title = $single->post_title;
		
		$output = "<div class='item'><a href='$url'>$title<img width='98%' src='$img'></a></div>" . PHP_EOL;
		return $output;
	}
	
	/**
	 * Function to define the option setting defaults
	 * 
	 * @since	1.0.0
	 * 
	 * @return   array    The options array.
	 */
	public function get_default_options() {
		$options = array(
			'id' => 1,
			'categories' => 'Uncategorized',
			'theme' => 'Original',
			'content' => '',
			'page_title' => 'Recipe Index',
			'url' => 'recipe-index'
			);
		return $options;
	}
	
	/**
	 * Function to add settings to database
	 * 
	 * @since 1.0.0
	 */
	public function options_init() {
		// set options equal to defaults
		$options = get_option( 'vrip_options' );
		if ( false === $options ){
			$options = $this->get_default_options();
		}
	}
}
