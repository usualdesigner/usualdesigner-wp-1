<?php
//add_image_size('portfolio',115,115);
	if(!function_exists('portfolio_item')){
		function portfolio_item($atts,$content){
			extract(shortcode_atts(array(
				'id'=>'',
				'slug' => ''
			),$atts));
			$query = '';
			if(!post_type_exists('portfolio')) {	
				return;
			}
			if( absint($id) > 0 ){
				$query = new WP_Query( array( 'post_type' => 'portfolio', 'post__in' => array($id )) );
			}elseif( strlen(trim($slug)) > 0 ){
				$_post = get_page_by_path($slug, OBJECT, 'portfolio');
				if( !is_null($_post) ){
					$query = new WP_Query( array( 'post_type' => 'portfolio', 'post__in' => array($_post->ID )) );
				}
			}
			if($query == '' || $query->posts_count() <=0 ) { return; }
			$count=0;
			if($query->have_posts()) : 
				while($query->have_posts()) : $query->the_post();
					$post_title = esc_html(get_the_title($post->ID));
					$post_url =  esc_url(get_permalink($post->ID));
					$url_video = esc_url(get_post_meta($post->ID,THEME_SLUG.'video_portfolio',true));
					$thumb=get_post_thumbnail_id($post->ID);
					$thumburl=wp_get_attachment_image_src($thumb,'blog_thumb');
				ob_start();
				?>
				<div class="portfolio_sc">
					<a class="image" href="<?php echo $post_url; ?>">
						<?php if($thumburl[0]) { ?>
							<img alt="<?php echo $post_title?>" title="<?php echo $post_title;?>" class="opacity_0" src="<?php echo  esc_url($thumburl[0]);?>"/>																
							<?php } else { ?>
							<img alt="<?php echo $post_title?>" title="<?php echo $post_title;?>" class="opacity_0" src="<?php echo get_template_directory_uri(); ?>/images/no-gallery-830x494.gif"/>
							<?php } ?>
						<div  class="hover-default"></div>
					</a>
					<div class="thumb-tag">
						<h2 class="post-title heading-title list-title portfolio-grid-title">
							<a  href="<?php echo $post_url; ?>">
							<?php echo $post_title; ?>
							</a>
						</h2>
					</div>
				</div>   				
				<?php
				endwhile;
			endif;
			$output = ob_get_contents();
			ob_end_clean();
			wp_reset_query();
			return $output;
		}
	}
	add_shortcode('portfolio_item','portfolio_item');
?>
