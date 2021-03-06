<?php 
// Create widget tabs post
if(!class_exists('WP_Widget_Best_Selling_Product')){
	class WP_Widget_Best_Selling_Product extends WP_Widget {
		function WP_Widget_Best_Selling_Product() {
			$widget_ops = array( 'classname' => 'wd_widget_best_selling_product woocommerce', 'description' => __( "Show Best Selling Products",'wpdance' ) );
			$this->WP_Widget('best_selling_product', __('WD - Best Selling Products','wpdance'), $widget_ops);
		}

		function widget( $args, $instance ) {
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			
			extract( $args );
			$title = apply_filters('widget_title', empty($instance['title']) ? __('Best Selling Products','wpdance') : $instance['title']);
			$row = empty($instance['row'])?4:absint($instance['row']);		
			$number = empty($instance['number'])?8:absint($instance['number']);	
			$is_slider = ($instance['is_slider'])?true:false;		
			$show_nav = ($instance['show_nav'])?true:false;	
			$auto_play = ($instance['auto_play'])?1:0;
			
			echo $before_widget;
			echo $before_title . $title . $after_title;
			
			wp_reset_query();
			global $post;
			$args = array(
				'post_type'	=> 'product',
				'post_status' => 'publish',
				'ignore_sticky_posts'	=> 1,
				'posts_per_page' => $number,
				'order' => 'desc',		
				'meta_key' 		 => 'total_sales',
				'orderby' 		 => 'meta_value_num',				
				'meta_query' => array(
					array(
						'key' => '_visibility',
						'value' => array('catalog', 'visible'),
						'compare' => 'IN'
					)
				)
			);
		
			$products = new WP_Query($args);
	?>
			<?php if($products->post_count>0){
			$i = 0; 
			$random_id = 'wd_widget_product_slider_wrapper_'.rand();
			?>
			<div class="wd_widget_product_slider_wrapper woocommerce <?php echo ($is_slider)?'loading':''; ?> <?php echo ($show_nav)?'has_navi':''; ?>" id="<?php echo $random_id; ?>" >
				<div class="widget_product_list_inner">
					<?php while ($products->have_posts()) : $products->the_post();?>
					<?php if( $i == 0 || $i % $row == 0 ){ ?>
					<div class="product_per_slide">
						<ul>
						<?php } ?>
							<li>
								<?php get_product_categories(); ?>
								<a class="thumbnail" href="<?php echo esc_url(get_permalink($post->ID)); ?>">
									<?php  
										if ( has_post_thumbnail() ) {
											the_post_thumbnail('prod_tini_thumb',array('title' => esc_attr(get_the_title()),'alt' => esc_attr(get_the_title()) ));
										} 
									?>
									<?php echo esc_attr(get_the_title($post->ID)); ?>
								</a>		
							
								<?php if(function_exists('wd_template_single_rating')) wd_template_single_rating(); ?>
								<?php woocommerce_template_loop_price(); ?>
							</li>
							<?php $i++;
							if ( $i % $row == 0 || $i == $products->post_count){ ?>
						</ul>
					</div>
					<?php } ?>
					<?php endwhile;?>
				</div>
				<?php if( $show_nav && $is_slider ): ?>
				<div class="slider_control">
					<a title="prev" class="prev" href="#">&lt;</a>
					<a title="next" class="next" href="#">&gt;</a>
				</div>
				<?php endif; ?>
			</div>
			<?php } ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
			<?php if( $is_slider ): ?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					"use strict";
					
					var $_this = jQuery('#<?php echo $random_id; ?>');
					var _auto_play = <?php echo $auto_play; ?> == 1;
					var owl = $_this.find('.widget_product_list_inner').owlCarousel({
								loop : true
								,items : 1
								,nav : false
								,dots : false
								,navSpeed : 1000
								,slideBy: 1
								,navRewind: false
								,autoplay: _auto_play
								,autoplayTimeout: 5000
								,autoplayHoverPause: true
								,autoplaySpeed: false // or number
								,mouseDrag: true
								,touchDrag: true
								,responsiveBaseElement: $_this
								,responsiveRefreshRate: 1000
								,onInitialized: function(){
									$_this.addClass('loaded').removeClass('loading');
								}
							});
							$_this.on('click', '.next', function(e){
								e.preventDefault();
								owl.trigger('next.owl.carousel');
							});

							$_this.on('click', '.prev', function(e){
								e.preventDefault();
								owl.trigger('prev.owl.carousel');
							});
				});
			</script>
			<?php endif; ?>
			<?php
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['row'] =  $new_instance['row'];
				$instance['number'] =  $new_instance['number'];									
				$instance['is_slider'] =  $new_instance['is_slider'];									
				$instance['show_nav'] =  $new_instance['show_nav'];									
				$instance['auto_play'] =  $new_instance['auto_play'];
				return $instance;
		}

		function form( $instance ) {
				//Defaults
				$instance_default = array(
										'title' 		=> 'Best Selling Products'
										,'row'			=> 2
										,'number'		=> 4
										,'is_slider'	=> 1
										,'show_nav'		=> 1
										,'auto_play'	=> 1
										);
				$instance = wp_parse_args( (array) $instance, $instance_default );
				$title = esc_attr( $instance['title'] );
				

	?>
				<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Enter your title','wpdance'); ?> : </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></p>
				<p>
					<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('A number of products','wpdance'); ?></label>
					<input class="widefat" type="text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('row'); ?>"><?php _e('A number of products per slide','wpdance'); ?></label>
					<input class="widefat" type="text" id="<?php echo $this->get_field_id('row'); ?>" name="<?php echo $this->get_field_name('row'); ?>" value="<?php echo $instance['row']; ?>" />
				</p>
				<hr />
				<p>
					<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('is_slider'); ?>" name="<?php echo $this->get_field_name('is_slider'); ?>" <?php echo ($instance['is_slider'])?'checked':''; ?> />
					<label for="<?php echo $this->get_field_id('is_slider'); ?>"><?php _e('Slider mode','wpdance'); ?></label>
				</p>
				<p>
					<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_nav'); ?>" name="<?php echo $this->get_field_name('show_nav'); ?>" <?php echo ($instance['show_nav'])?'checked':''; ?> />
					<label for="<?php echo $this->get_field_id('show_nav'); ?>"><?php _e('Show navigation button','wpdance'); ?></label>
				</p>
				<p>
					<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('auto_play'); ?>" name="<?php echo $this->get_field_name('auto_play'); ?>" <?php echo ($instance['auto_play'])?'checked':''; ?> />
					<label for="<?php echo $this->get_field_id('auto_play'); ?>"><?php _e('Auto play','wpdance'); ?></label>
				</p>
				

	<?php
		}
	}
}
?>