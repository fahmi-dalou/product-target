<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/
 * @since      1.0.0
 *
 * @package    Product_target
 * @subpackage Product_target/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Product_target
 * @subpackage Product_target/admin
 * @author     fahmi dalou <eng.fahmi.r.dalou@gmail.com>
 */
class Product_target_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu', array($this,'product_target_register_options_page'));
		add_action( 'admin_init', array($this,'register_product_target_settings'));

	}
	public function product_target_register_options_page() {
	  add_menu_page('Product target', 'Product target settings', 'manage_options', 'product_setting',  array($this,'product_target_options_page'));
	}
	
	public function register_product_target_settings() {
		//register our settings
		register_setting( 'product_target-settings-group', 'product_target_field' );
	}
	/**
	 * Creating html of option page
	 *
	 */
	public function product_target_options_page()
	{
		?>
		  <div>
			  <h2><?php _e( "Product Target Setting", "product_target" ); ?></h2>
			  	<!-- Starting of option form -->
				<form method="post" action="options.php">
					<?php settings_fields( 'product_target-settings-group' ); ?>
    				<?php do_settings_sections( 'product_target-settings-group' ); ?>

					  <h3><?php _e( "Default product target category", "product_target" ); ?></h3>

					  <?php 
					  // get all terms inside target_groups taxonomy
					  $terms = get_terms( array(
						    'taxonomy' => 'target_groups',
						    'hide_empty' => false,
						) );
					  ?>
					  <?php
					  	// Verify target_groups has terms. 
					  	if (count($terms) > 0){
					  ?>
					  	<select name="product_target_field">
						  	<option value="0"><?php _e( "Select product target", "product_target" ); ?></option>

						  	<?php foreach ($terms as $term) { ?>

						  		<option value="<?php echo $term->term_id ?>" <?php  echo ($term->term_id == esc_attr( get_option('product_target_field') ) ) ? "selected" : "" ; ?>>

						  			<?php echo $term->name ?>	

						  		</option>
						  		
						  	<?php } ?>
						 </select>
					  <?php }else{ ?>

					  			<p><?php _e( "the products doesn't have any product target group", "product_target" ); ?></p>

					  <?php }?>
					  
			  	  <?php  submit_button(); ?>
			  </form>
		  </div>
		<?php
	} 

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Product_target_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Product_target_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/product_target-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Product_target_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Product_target_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/product_target-admin.js', array( 'jquery' ), $this->version, false );

	}

}
