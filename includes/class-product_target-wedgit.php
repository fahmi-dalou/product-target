<?php

/**
 * Creating the widget 
 *
 * @link       https://wordpress.org/plugins/
 * @since      1.0.0
 *
 * @package    Product_target
 * @subpackage Product_target/includes
 */

/**
 * Creating the widget for product list
 *
 * @package    Product_target
 * @subpackage Product_target/includes
 * @author     fahmi dalou <eng.fahmi.r.dalou@gmail.com>
 */
class product_list extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'product_list', 
		 
		// Widget name will appear in UI
		__('Product List', 'product_list_domain'), 
		 
		// Widget description
		array( 'description' => __( 'Display product list', 'product_list_domain' ), ) 
		);
	}
	 
	// Creating widget front-end
	 
	public function widget( $args, $instance ) {

		// the title from wedgit instance
		$title = apply_filters( 'widget_title', $instance['title'] );

		// the default product target from setting page
		$default_term  = esc_attr( get_option('product_target_field') );


		// Verify that the GET has value and not 'target_that_doesnt_exist' is valid.
		if (isset($_GET["target"])&& $_GET["target"] != "target_that_doesnt_exist") {

			// Verify that the slug exist
			if(get_term_by('slug', $_GET["target"], 'target_groups')){

				$target = get_term_by('slug', $_GET["target"], 'target_groups')->term_id;

			}else{

				if ( $default_term != 0 && $default_term ) {

					$target = $default_term;	

				}else{

					$target = "";	

				}
			}
			
		}else{

			if ( $default_term != 0 && $default_term ) {

				$target = $default_term;	

			}else{

				$target = "";	

			}
		
		}
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];

		
		ob_start();


		?>
			<?php 
			// the target will equal '' if the $default term = 0 or not exist
			if($target == ""){
				$args_query = array(
						'post_type' => 'product',
						'posts_per_page' => 5,
						'meta_key' => 'rating_field',
			            'orderby' => 'meta_value_num',
			            'order' => 'DESC'
						);

			}else{
				$args_query = array(
						'post_type' => 'product',
						'posts_per_page' => 5,
						'meta_key' => 'rating_field',
			            'orderby' => 'meta_value_num',
			            'order' => 'DESC',
						'tax_query' => array(
						    array(
						    'taxonomy' => 'target_groups',
						    'field' => 'term_id',
						    'terms' => $target
						     )
						  )
						);
			}
			
			$product_query = new WP_Query($args_query);

			// Verify that the query contain products
			if ($product_query->have_posts()) {
			
				while($product_query->have_posts()) : $product_query->the_post(); ?>

					<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

						<!-- // Verify that the thumbnail exist -->
						<?php if (has_post_thumbnail()) { ?>

							<img src="<?php the_post_thumbnail_url(); ?>" alt="">

						<?php }?>

						<?php $value = get_post_meta( get_the_ID(), 'rating_field', true ); ?>

						<ul class="rating">

							<?php for ($i=1; $i <= 5 ; $i++) { ?>

								<li><i class="fas fa-star <?php echo ($i <= $value ) ? 'active' : '' ;?>"></i></li>

							<?php } ?>

						</ul>

						<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

					</div>

				<?php endwhile; ?>

			<?php } else { ?>

				<h3> this product target doesn't have any product </h3>
				
			<?php } ?>
			
			<?php wp_reset_postdata(); // reset the query ?>

		<?php
			echo $args['after_widget'];

			$output_string = ob_get_contents();

			ob_end_clean();

			echo $output_string;

	}
	         
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {

			$title = $instance[ 'title' ];

		}
		else {

			$title = __( 'New title', 'product_list_domain' );
			
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
	     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class product_list ends here