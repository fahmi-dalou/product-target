<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wordpress.org/plugins/
 * @since      1.0.0
 *
 * @package    Product_target
 * @subpackage Product_target/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Product_target
 * @subpackage Product_target/includes
 * @author     fahmi dalou <eng.fahmi.r.dalou@gmail.com>
 */
class Product_target {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Product_target_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PRODUCT_TARGET_VERSION' ) ) {
			$this->version = PRODUCT_TARGET_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'product_target';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Product_target_Loader. Orchestrates the hooks of the plugin.
	 * - Product_target_i18n. Defines internationalization functionality.
	 * - Product_target_Admin. Defines all hooks for the admin area.
	 * - Product_target_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product_target-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product_target-i18n.php';

		/**
		 * The class responsible for creating product list wedgit
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product_target-wedgit.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-product_target-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-product_target-public.php';

		$this->loader = new Product_target_Loader();

		add_action( 'init', array($this , 'product_CPT' ), 0 );
		add_action( 'init', array($this , 'create_target_groups_hierarchical_taxonomy' ) );
		add_action( 'save_post', array($this ,'save_rating_field_meta_box_data') );
		add_filter( 'the_content', array($this ,'rating_field_before_post') );
		add_action( 'widgets_init', array($this , 'product_list_load_widget' ), 0 );


	}

	/**
	 * Create Product CPT
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	 
	public function product_CPT() {
	 
	// Set UI labels for Custom Post Type
	    $labels = array(
	        'name'                => _x( 'Products', 'Post Type General Name', 'product_target' ),
	        'singular_name'       => _x( 'product', 'Post Type Singular Name', 'product_target' ),
	        'menu_name'           => __( 'Products', 'product_target' ),
	        'parent_item_colon'   => __( 'Parent product', 'product_target' ),
	        'all_items'           => __( 'All Products', 'product_target' ),
	        'view_item'           => __( 'View product', 'product_target' ),
	        'add_new_item'        => __( 'Add New product', 'product_target' ),
	        'add_new'             => __( 'Add New', 'product_target' ),
	        'edit_item'           => __( 'Edit product', 'product_target' ),
	        'update_item'         => __( 'Update product', 'product_target' ),
	        'search_items'        => __( 'Search product', 'product_target' ),
	        'not_found'           => __( 'Not Found', 'product_target' ),
	        'not_found_in_trash'  => __( 'Not found in Trash', 'product_target' ),
	    );
	     
	// Set other options for Custom Post Type
	     
	    $args = array(
	        'label'               => __( 'Products', 'product_target' ),
	        'description'         => __( 'Products items', 'product_target' ),
	        'labels'              => $labels,
	        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail' ),
	        'register_meta_box_cb' => array($this,'rating_field_meta_box'),
	        'hierarchical'        => false,
	        'public'              => true,
	        'show_ui'             => true,
	        'show_in_menu'        => true,
	        'show_in_nav_menus'   => true,
	        'show_in_admin_bar'   => true,
	        'menu_icon'           => 'dashicons-products',
	        'menu_position'       => 5,
	        'can_export'          => true,
	        'has_archive'         => true,
	        'exclude_from_search' => false,
	        'publicly_queryable'  => true,
	        'capability_type'     => 'page',
	    );
	     
	    // Registering your Custom Post Type
	    register_post_type( 'product', $args );
	 
	}

	public function create_target_groups_hierarchical_taxonomy() {

	// Add new taxonomy, make it hierarchical like categories
	//first do the translations part for GUI

	  $labels = array(
	    'name' => _x( 'Target groups', 'taxonomy general name' ),
	    'singular_name' => _x( 'Target groups', 'taxonomy singular name' ),
	    'search_items' =>  __( 'Search Target groups' ),
	    'all_items' => __( 'All Target groups' ),
	    'parent_item' => __( 'Parent Target groups' ),
	    'parent_item_colon' => __( 'Parent Target groups:' ),
	    'edit_item' => __( 'Edit Target groups' ), 
	    'update_item' => __( 'Update Target groups' ),
	    'add_new_item' => __( 'Add New Target groups' ),
	    'new_item_name' => __( 'New Target groups Name' ),
	    'menu_name' => __( 'Target groups' ),
	  ); 	

	// Now register the taxonomy

	  register_taxonomy('target_groups',array('product'), array(
	    'hierarchical' => true,
	    'labels' => $labels,
	    'show_ui' => true,
	    'show_admin_column' => true,
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'target_group' ),
	  ));

	}

		/**
	 * Add rating filed meta box
	 *
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function rating_field_meta_box() {

	    add_meta_box(
	        'rating-meta',
	        __( 'Rating', 'sitepoint' ),
	        array($this,'rating_field_meta_box_callback'),
	        'product',
	        'side',
	        'core'
	    );

	}


	/**
	 * Add HTML rating filed to meta box 
	 *
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function rating_field_meta_box_callback( $post ) {

	    // Add a nonce field so we can check for it later.
	    wp_nonce_field( 'rating_field_nonce', 'rating_field_nonce' );

	    $value = get_post_meta( $post->ID, 'rating_field', true );
	    if (!empty($value)&& $value != '') {
	    	echo '<input type="number" id="rating_field" name="rating_field" min="1" max="5" value="'.esc_attr( $value ) .'"> ';
	    }else{
	    	echo '<input type="number" id="rating_field" name="rating_field" min="1" max="5" value="1"> ';
	    }

	    
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id
	 */
	public function save_rating_field_meta_box_data( $post_id ) {

	    // Check if our nonce is set.
	    if ( ! isset( $_POST['rating_field_nonce'] ) ) {
	        return;
	    }

	    // Verify that the nonce is valid.
	    if ( ! wp_verify_nonce( $_POST['rating_field_nonce'], 'rating_field_nonce' ) ) {
	        return;
	    }

	    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	        return;
	    }

	    // Check the user's permissions.
	    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

	        if ( ! current_user_can( 'edit_page', $post_id ) ) {
	            return;
	        }

	    }
	    else {

	        if ( ! current_user_can( 'edit_post', $post_id ) ) {
	            return;
	        }
	    }

	    // Make sure that it is set.
	    if ( ! isset( $_POST['rating_field'] ) ) {
	        return;
	    }

	    // Sanitize user input.
	    $my_data = sanitize_text_field( $_POST['rating_field'] );

	    // Update the meta field in the database.
	    update_post_meta( $post_id, 'rating_field', $my_data );
	}


	public function rating_field_before_post( $content ) {

	    global $post;

	    // retrieve the Rating for the current post
	    $rating_field = esc_attr( get_post_meta( $post->ID, 'rating_field', true ) );

	    $notice = "<div class='sp_rating_field'>$rating_field</div>";

	    return $notice . $content;

	}

	/**
	 * load product list widget
	 *
	 */

	public function product_list_load_widget() {
	    register_widget( 'product_list' );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Product_target_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Product_target_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Product_target_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Product_target_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Product_target_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
