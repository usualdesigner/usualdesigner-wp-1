<?php
/**
 * Tab Product Widget
 */
if(!class_exists('WP_Widget_Tab_Product')){
	class WP_Widget_Tab_Product extends WP_Widget {

		function WP_Widget_Tab_Product() {
			$widgetOps = array('classname' => 'wd_widget_tab_product woocommerce', 'description' => __('Display WooCommerce Product width Multi Tab','wpdance'));
			$this->WP_Widget('wd_tab_product', __('WD - Tab Product','wpdance'), $widgetOps);
		}

		function widget( $args, $instance ) {
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			
			extract($args);
			$title = '';
			
			$show_new = ($instance['new'])?true:false;		
			$new_title = ($instance['new_title'])?esc_attr($instance['new_title']):'New';
			
			$show_feature = ($instance['feature'])?true:false;		
			$feature_title = ($instance['feature_title'])?esc_attr($instance['feature_title']):'Feature';
			
			$show_hot = ($instance['hot'])?true:false;		
			$hot_title = ($instance['hot_title'])?esc_attr($instance['hot_title']):'Hot';
			
			$show_best_selling = ($instance['best_selling'])?true:false;		
			$best_selling_title = ($instance['best_selling_title'])?esc_attr($instance['best_selling_title']):'Best Selling';
			
			$show_sale = ($instance['sale'])?true:false;		
			$sale_title = ($instance['sale_title'])?esc_attr($instance['sale_title']):'Sale';
			
			$tabs = array();
			if( $show_new ){
				$tabs['type'][] 	= 'new';
				$tabs['title'][] 	= $new_title;
				$tabs['id'][] 		= 'new_'.rand(0,10000);
			}
			if( $show_feature ){
				$tabs['type'][] 	= 'feature';
				$tabs['title'][] 	= $feature_title;
				$tabs['id'][] 		= 'feature_'.rand(0,10000);
			}
			if( $show_hot ){
				$tabs['type'][] 	= 'hot';
				$tabs['title'][] 	= $hot_title;
				$tabs['id'][] 		= 'hot_'.rand(0,10000);
			}
			if( $show_best_selling ){
				$tabs['type'][] 	= 'best_selling';
				$tabs['title'][] 	= $best_selling_title;
				$tabs['id'][] 		= 'best_selling_'.rand(0,10000);
			}
			if( $show_sale ){
				$tabs['type'][] 	= 'sale';
				$tabs['title'][] 	= $sale_title;
				$tabs['id'][] 		= 'sale_'.rand(0,10000);
			}
			
			if( empty($tabs) ) /* No tab product to show */
				return;
			
			$title .= '<span class="wd_tab_product_title">';
			foreach( $tabs['title'] as $key => $value ){
				$class = ($key == 0)?'current':'';
				$title .= '<a class="tab_item '.$class.'" data-id="'.$tabs['id'][$key].'" href="#">'.$value.'</a>';
			}
			$title .= '</span>';
			
			$categories = $instance['categories'];
			
			$limit = empty($instance['limit'])?6:absint($instance['limit']);		
			$show_thumbnail = (isset($instance['show_thumbnail']) && $instance['show_thumbnail'])?true:false;		
			$show_categories = (isset($instance['show_categories']) && $instance['show_categories'])?true:false;		
			$show_product_title = (isset($instance['show_product_title']) && $instance['show_product_title'])?true:false;		
			$show_price = (isset($instance['show_price']) && $instance['show_price'])?true:false;		
			$show_rating = (isset($instance['show_rating']) && $instance['show_rating'])?true:false;		
			
			wp_reset_query();
			?>
			<?php echo $before_widget;?>
			<?php echo $before_title . $title . $after_title;?>
			<?php 
				global $post;
				$tax_query = array();
				if( is_array($categories) && count($categories) >= 0){
					$tax_query =  array(
										array(
											'taxonomy' => 'product_cat'
											,'terms' => $categories
											,'field' => 'term_id'
										)
									);
				}
				echo '<div class="tab_content">';
				foreach( $tabs['type'] as $key => $product_type ){
					$args = array(
						'post_type'	=> 'product',
						'post_status' => 'publish',
						'ignore_sticky_posts'	=> 1,
						'posts_per_page' => $limit,
						'orderby' => 'date',
						'order' => 'desc',				
						'meta_query' => array(
							array(
								'key' => '_visibility',
								'value' => array('catalog', 'visible'),
								'compare' => 'IN'
							)
						)
					);
					
					switch( $product_type ){
						case 'feature':
							$args['meta_query'][] = array(
								'key' 			=> '_featured'
								,'value' 		=> 'yes'
							);
						break;
						case 'hot':
							add_filter( 'posts_clauses', array($this,'wd_order_by_rating_post_clauses') );
						break;
						case 'best_selling':
							$args['order'] = 'desc';
							$args['meta_key'] = 'total_sales';
							$args['orderby'] = 'meta_value_num';
						break;
						case 'sale':
							$args['meta_query'][] = array(
								'key' 			=> '_sale_price'
								,'value' 		=>  0
								,'compare'   	=> '>'
								,'type'      	=> 'NUMERIC'
							);
						break;
						default: /* Recent Product - New Product */
						break;
					}
					
					if( !empty($tax_query) ){
						$args['tax_query'] = $tax_query;
					}
					
					$result = new WP_Query( $args );
					if( $product_type == 'hot' ){
						remove_filter( 'posts_clauses', array($this,'wd_order_by_rating_post_clauses') );
					}
					
					$style = ($key == 0)?'display: block;':'display: none;';
					?>
						
						<ul class="product_list_widget" id="<?php echo $tabs['id'][$key]; ?>" style="<?php echo $style; ?>">
						<?php if( $result->have_posts() ){ while( $result->have_posts() ){ $result->the_post();
							$GLOBALS['product'] = wc_get_product($post->ID);
						?>	
								<li>
									<?php if($show_categories) get_product_categories(); ?>
									<a class="thumbnail" href="<?php echo esc_url(get_permalink($post->ID)); ?>">
										<?php  
											if ( has_post_thumbnail() && $show_thumbnail ) {
												the_post_thumbnail('prod_tini_thumb',array('title' => esc_attr(get_the_title()),'alt' => esc_attr(get_the_title()) ));
											} 
										?>
										<?php if($show_product_title) echo esc_attr(get_the_title($post->ID)); ?>
									</a>		
							
								<?php if(function_exists('wd_template_single_rating') && $show_rating) wd_template_single_rating(); ?>
								<?php if($show_price) woocommerce_template_loop_price(); ?>
								</li>
							<?php
								
							}
						}
						?>
						</ul>
						
					<?php
					wp_reset_query();
				}
				echo '</div>';
				?>
			<div class="clear"></div>

			<?php
			echo $after_widget;
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					"use strict";
					
					$('.wd_widget_tab_product .wd_tab_product_title a').bind('click', function(e){
						e.preventDefault();
						if( $(this).hasClass('current') )
							return;
						var current_id = $(this).siblings('a.current').attr('data-id');
						var new_id = $(this).attr('data-id');
						
						$(this).siblings('a.current').removeClass('current');
						$(this).addClass('current');
						
						$('.tab_content ul#'+current_id).fadeOut(300, function(){
							$('.tab_content ul#'+new_id).fadeIn(200);
						});
					});
				});
			</script>
			<?php
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;	
			$instance['new'] 					=  $new_instance['new'];
			$instance['new_title'] 				=  $new_instance['new_title'];
			$instance['feature'] 				=  $new_instance['feature'];
			$instance['feature_title'] 			=  $new_instance['feature_title'];
			$instance['hot'] 					=  $new_instance['hot'];
			$instance['hot_title'] 				=  $new_instance['hot_title'];
			$instance['best_selling'] 			=  $new_instance['best_selling'];
			$instance['best_selling_title'] 	=  $new_instance['best_selling_title'];
			$instance['sale'] 					=  $new_instance['sale'];
			$instance['sale_title'] 			=  $new_instance['sale_title'];
			$instance['categories'] 			=  $new_instance['categories'];
			$instance['limit'] 					=  $new_instance['limit'];									
			$instance['show_thumbnail'] 		=  $new_instance['show_thumbnail'];									
			$instance['show_categories'] 		=  $new_instance['show_categories'];									
			$instance['show_product_title'] 	=  $new_instance['show_product_title'];									
			$instance['show_price'] 			=  $new_instance['show_price'];									
			$instance['show_rating'] 			=  $new_instance['show_rating'];									
			
												
			return $instance;
		}
		
		function wd_order_by_rating_post_clauses( $args ) {

			global $wpdb;

			$args['where'] .= " AND $wpdb->commentmeta.meta_key = 'rating' ";

			$args['join'] .= "
				LEFT JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
				LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
			";

			$args['orderby'] = "$wpdb->commentmeta.meta_value DESC";

			$args['groupby'] = "$wpdb->posts.ID";

			return $args;
		}
		
		function get_list_categories($cat_parent_id){
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return array();
			}
			$args = array(
					'taxonomy' =>'product_cat'
					,'hierarchical'=>1
					,'parent'=>$cat_parent_id
					,'title_li'=>''
					,'child_of'=>0
				);
			$cats = get_categories($args);
			return $cats;
		}
		function get_list_sub_categories($cat_parent_id,$instance){
			$sub_categories = $this->get_list_categories($cat_parent_id); 
			if( count($sub_categories) > 0){
			?>
				<ul class="children">
					<?php foreach( $sub_categories as $sub_cat ){ ?>
						<li>
							<label>
								<input type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[<?php $sub_cat->term_id; ?>]" value="<?php echo $sub_cat->term_id; ?>" <?php echo (in_array($sub_cat->term_id,$instance['categories']))?'checked':''; ?> />
								<?php echo $sub_cat->name; ?>
							</label>
							<?php $this->get_list_sub_categories($sub_cat->term_id,$instance); ?>
						</li>
					<?php } ?>
				</ul>
			<?php }
		}

		function form( $instance ) {
			$array_default = array(
							'new'					=> 1
							,'new_title'			=> 'New'
							,'feature'				=> 1
							,'feature_title'		=> 'Feature'
							,'hot'					=> 1
							,'hot_title'			=> 'Hot'
							,'best_selling'			=> 1
							,'best_selling_title'	=> 'Best Selling'
							,'sale'					=> 1
							,'sale_title'			=> 'Sale'
							,'categories' 			=> array()
							,'limit'				=> 6
							,'show_thumbnail' 		=> 1
							,'show_categories' 		=> 1
							,'show_product_title' 	=> 1
							,'show_price' 			=> 1
							,'show_rating' 			=> 1
							
							);
			$product_cats = $this->get_list_categories(0);
			$instance = wp_parse_args( (array) $instance, $array_default );
			$instance['new_title'] 			= esc_attr($instance['new_title']);
			$instance['feature_title'] 		= esc_attr($instance['feature_title']);
			$instance['hot_title'] 			= esc_attr($instance['hot_title']);
			$instance['best_selling_title'] = esc_attr($instance['best_selling_title']);
			$instance['sale_title'] 		= esc_attr($instance['sale_title']);
			$instance['limit'] 				= absint($instance['limit']);
			if( !is_array($instance['categories']) )
				$instance['categories'] = array();
		?>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('new'); ?>" name="<?php echo $this->get_field_name('new'); ?>" <?php echo ($instance['new'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('new'); ?>"><?php _e('Show New Product','wpdance'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('new_title'); ?>"><?php _e('New Product Tab Title','wpdance'); ?> : </label>
				<input class="widefat" id="<?php echo $this->get_field_id('new_title'); ?>" name="<?php echo $this->get_field_name('new_title'); ?>" type="text" value="<?php echo $instance['new_title']; ?>" />
			</p>
		
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('feature'); ?>" name="<?php echo $this->get_field_name('feature'); ?>" <?php echo ($instance['feature'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('feature'); ?>"><?php _e('Show Feature Product','wpdance'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('feature_title'); ?>"><?php _e('Feature Product Tab Title','wpdance'); ?> : </label>
				<input class="widefat" id="<?php echo $this->get_field_id('feature_title'); ?>" name="<?php echo $this->get_field_name('feature_title'); ?>" type="text" value="<?php echo $instance['feature_title']; ?>" />
			</p>
			
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('hot'); ?>" name="<?php echo $this->get_field_name('hot'); ?>" <?php echo ($instance['hot'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('hot'); ?>"><?php _e('Show Hot Product','wpdance'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('hot_title'); ?>"><?php _e('Hot Product Tab Title','wpdance'); ?> : </label>
				<input class="widefat" id="<?php echo $this->get_field_id('hot_title'); ?>" name="<?php echo $this->get_field_name('hot_title'); ?>" type="text" value="<?php echo $instance['hot_title']; ?>" />
			</p>
			
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('best_selling'); ?>" name="<?php echo $this->get_field_name('best_selling'); ?>" <?php echo ($instance['best_selling'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('best_selling'); ?>"><?php _e('Show Best Selling Product','wpdance'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('best_selling_title'); ?>"><?php _e('Best Selling Product Tab Title','wpdance'); ?> : </label>
				<input class="widefat" id="<?php echo $this->get_field_id('best_selling_title'); ?>" name="<?php echo $this->get_field_name('best_selling_title'); ?>" type="text" value="<?php echo $instance['best_selling_title']; ?>" />
			</p>
			
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('sale'); ?>" name="<?php echo $this->get_field_name('sale'); ?>" <?php echo ($instance['sale'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('sale'); ?>"><?php _e('Show Sale Product','wpdance'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('sale_title'); ?>"><?php _e('Sale Product Tab Title','wpdance'); ?> : </label>
				<input class="widefat" id="<?php echo $this->get_field_id('sale_title'); ?>" name="<?php echo $this->get_field_name('sale_title'); ?>" type="text" value="<?php echo $instance['sale_title']; ?>" />
			</p>
			
			<p>
				<label><?php _e('Select Categories','wpdance'); ?> : </label>
				<div class="categorydiv">
					<div class="tabs-panel">
						<ul class="categorychecklist">
							<?php foreach($product_cats as $cat){ ?>
							<li>
								<label>
									<input type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[<?php $cat->term_id; ?>]" value="<?php echo $cat->term_id; ?>" <?php echo (in_array($cat->term_id,$instance['categories']))?'checked':''; ?> />
									<?php echo $cat->name; ?>
								</label>
								<?php $this->get_list_sub_categories($cat->term_id,$instance); ?>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<p class="description"><?php _e("Don't select to filter by all categories","wpdance"); ?></p>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of products for each type','wpdance'); ?></label>
				<input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo $instance['limit']; ?>" />
			</p>
			
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_thumbnail'); ?>" <?php echo ($instance['show_thumbnail'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php _e('Show thumbnail','wpdance'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_categories'); ?>" name="<?php echo $this->get_field_name('show_categories'); ?>" <?php echo ($instance['show_categories'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_categories'); ?>"><?php _e('Show categories','wpdance'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_product_title'); ?>" name="<?php echo $this->get_field_name('show_product_title'); ?>" <?php echo ($instance['show_product_title'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_product_title'); ?>"><?php _e('Show product title','wpdance'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_price'); ?>" name="<?php echo $this->get_field_name('show_price'); ?>" <?php echo ($instance['show_price'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_price'); ?>"><?php _e('Show price','wpdance'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_rating'); ?>" name="<?php echo $this->get_field_name('show_rating'); ?>" <?php echo ($instance['show_rating'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_rating'); ?>"><?php _e('Show rating','wpdance'); ?></label>
			</p>
			
			<?php }
	}
}

