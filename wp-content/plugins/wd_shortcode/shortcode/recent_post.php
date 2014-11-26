<?php 
if(!function_exists ('recent_blogs_functions')){
	function recent_blogs_functions($atts,$content = false){
		extract(shortcode_atts(array(
			'category'		=>	''
			,'columns'		=> 4
			,'number_posts'	=> 4
			,'layout'		=> 'vertical'
			,'title'		=> 'yes'
			,'thumbnail'	=> 'yes'
			,'meta'			=> 'yes'
			,'excerpt'		=> 'yes'
			,'tag'		=> 'yes'
			,'sharing'		=> 'yes'
			,'excerpt_words'=> 20
		),$atts));

		wp_reset_query();	

		$args = array(
				'post_type' 			=> 'post'
				,'ignore_sticky_posts' 	=> 1
				,'showposts' 			=> $number_posts
		);	
		if( strlen($category) > 0 ){
			$args = array(
				'post_type' 			=> 'post'
				,'ignore_sticky_posts' 	=> 1
				,'showposts' 			=> $number_posts
				,'category_name' 		=> $category
			);	
		}		
		$title = strcmp('yes',$title) == 0 ? 1 : 0;
		$thumbnail = strcmp('yes',$thumbnail) == 0 ? 1 : 0;
		$meta = strcmp('yes',$meta) == 0 ? 1 : 0;
		$excerpt = strcmp('yes',$excerpt) == 0 ? 1 : 0;
		$tag = strcmp('yes',$tag) == 0 ? 1 : 0;
		$sharing = strcmp('yes',$sharing) == 0 ? 1 : 0;
		
		$span_class = "col-sm-".(24/$columns);
		
		$num_count = count(query_posts($args));	
		if( have_posts() ) :
			$id_widget = 'recent-blogs-shortcode'.rand(0,1000);
			ob_start();
			echo '<ul id="'. $id_widget .'" class="shortcode-recent-blogs columns-'.$columns.' layout_'.$layout.'">';
			$i = 0;
			while(have_posts()) {
				the_post();
				global $post;
				?>
				<?php if($layout=="horizontal"): ?>
					<li class="item <?php echo $span_class ?><?php if( $i == 0 || $i % $columns == 0 ) echo ' first';?><?php if( $i == $num_count-1 || $i % $columns == $columns-1 ) echo ' last';?>">
						<div class="image_wrapper<?php if(!$thumbnail) echo ' noimage';?>">
							<div class="image <?php if(!$thumbnail) echo "hidden"?>">
								<a class="thumbnail" href="<?php the_permalink(); ?>">
									<?php 
										the_post_thumbnail('blog_shortcode',array('class' => 'thumbnail-effect-1'));
									?>
									<div class="thumbnail-effect"></div>
								</a>								
							</div>
						</div>
						
						<div class="blog_wrapper_horizontal">
							<h1 class="heading-title <?php if(!$title) echo 'hidden'; ?>"><a href="<?php echo get_permalink($post->ID); ?>" class="wpt_title"  ><?php echo get_the_title($post->ID); ?></a></h1>
							
							<div class="info-detail <?php if(!$meta) echo 'hidden'; ?>">
								<span class="author">	
								<?php _e('','wpdance'); ?> 
								<?php the_author_posts_link(); ?> 
								</span>
								
								<span class="date-time <?php if(!$excerpt) echo 'hidden'; ?>"><?php the_time(get_option('date_format')); ?></span>
								
								<span class="comments-count">
								<span class="number"><?php $comments_count = wp_count_comments($post->ID); if($comments_count->approved < 10 && $comments_count->approved > 0) echo '0'; echo $comments_count->approved;?></span>
								<?php _e('comment(s)','wpdance'); ?>
								</span>
							</div>
							
							<div class="excerpt <?php if(!$excerpt) echo 'hidden'; ?>"><?php the_excerpt_max_words($excerpt_words); ?></div>
							<div class="bottom-share <?php if((!$tag) && (!$sharing)) echo 'hidden'; ?>">
								<div class="tag_blog <?php if(!$tag) echo 'hidden'; ?>">
									<?php the_tags(__('Tags ','wpdance')," "); ?>
								</div>
								<!-- WIDGET SOCIAL -->
								<?php if( function_exists('wd_template_social_sharing') ){ ?>
									<div class="sharing_blog <?php if(!$sharing) echo 'hidden'; ?>">
									<?php wd_template_social_sharing(); ?>
									</div>
								<?php } ?>
								
							</div>
						</div>
					</li>
				
				<?php elseif($layout=="vertical"):?>
				
					<li class="item <?php echo $span_class ?><?php if( $i == 0 || $i % $columns == 0 ) echo ' first';?><?php if( $i == $num_count-1 || $i % $columns == $columns-1 ) echo ' last';?>">
							<h1 class="heading-title <?php if(!$title) echo 'hidden'; ?>"><a href="<?php echo get_permalink($post->ID); ?>" class="wpt_title"  ><?php echo get_the_title($post->ID); ?></a></h1>
							
							
							<div class="info-detail <?php if(!$meta) echo 'hidden'; ?>">
								<span class="author">	
								<?php _e('','wpdance'); ?> 
								<?php the_author_posts_link(); ?> 
								</span>
								
								<span class="date-time <?php if(!$excerpt) echo 'hidden'; ?>"><?php the_time(get_option('date_format')); ?></span>
								
								<span class="comments-count">
								<span class="number"><?php $comments_count = wp_count_comments($post->ID); if($comments_count->approved < 10 && $comments_count->approved > 0) echo '0'; echo $comments_count->approved;?></span>
								<?php _e('comment(s)','wpdance'); ?>
								</span>
							</div>
								
							
							<div class="image_wrapper<?php if(!$thumbnail) echo ' noimage';?>">
								<div class="image <?php if(!$thumbnail) echo "hidden"?>">
									<a class="thumbnail" href="<?php the_permalink(); ?>">
										<?php 
											the_post_thumbnail('blog_shortcode',array('class' => 'thumbnail-effect-1'));
										?>
										<div class="thumbnail-effect"></div>
									</a>								
								</div>
							</div>	
							
							<div class="excerpt <?php if(!$excerpt) echo 'hidden'; ?>"><?php the_excerpt_max_words($excerpt_words); ?></div>
							<div class="bottom-share <?php if((!$tag) && (!$sharing)) echo 'hidden'; ?>">
								<div class="tag_blog <?php if(!$tag) echo 'hidden'; ?>">
									<?php the_tags(__('Tags ','wpdance')," "); ?>
								</div>
								<!-- WIDGET SOCIAL -->
								<?php if( function_exists('wd_template_social_sharing') ){ ?>
									<div class="sharing_blog <?php if(!$sharing) echo 'hidden'; ?>">
									<?php wd_template_social_sharing(); ?>
									</div>
								<?php } ?>
								
							</div>
					
					</li>
					
				<?php endif;?>
		<?php
			$i++;
			}
			echo '</ul>';
			$ret_html = ob_get_contents();
			ob_end_clean();
			//ob_end_flush();
		endif;
		wp_reset_query();
		return $ret_html;
	}
} 
add_shortcode('recent_blogs','recent_blogs_functions');


if(!function_exists ('recent_portfolios_function')){
	function recent_portfolios_function($atts,$content = false){
		extract(shortcode_atts(array(
			'gallery'		=>	''
			,'columns'		=> 1
			,'number_posts'	=> 4
			,'title'		=> 'yes'
			,'thumbnail'	=> 'yes'
			,'meta'			=> 'yes'
			,'excerpt'		=> 'yes'
			,'excerpt_words'=> 30
		),$atts));

		wp_reset_query();	

		$args = array(
				'post_type' 		=> 'portfolio'
				,'showposts' 		=> $number_posts
		);	
		if( strlen($gallery) > 0 ){
			$args = array(
				'post_type' 		=> 'portfolio'
				,'showposts' 		=> $number_posts
				,'tax_query' => array(
					array(
						'taxonomy' => 'gallery'
						,'field' => 'slug'
						,'terms' => $gallery
					)
				)
			);	
		}		
		$title = strcmp('yes',$title) == 0 ? 1 : 0;
		$thumbnail = strcmp('yes',$thumbnail) == 0 ? 1 : 0;
		$meta = strcmp('yes',$meta) == 0 ? 1 : 0;
		$excerpt = strcmp('yes',$excerpt) == 0 ? 1 : 0;
		
		$span_class = "span".(12/$columns);
		
		$num_count = count(query_posts($args));	
		if( have_posts() ) :
			$id_widget = 'recent-portfolios-shortcode'.rand(0,1000);
			ob_start();
			echo '<ul id="'. $id_widget .'"class="shortcode-recent-portfolios columns-'.$columns.'">';
			$i = 0;
			while(have_posts()) {
				the_post();
				global $post;
				$post_title = get_the_title($post->ID);
				$post_url =  get_permalink($post->ID);
				$url_video = get_post_meta($post->ID,THEME_SLUG.'video_portfolio',true);
				$term_list = implode( ' ', wp_get_post_terms($post->ID, 'gallery', array("fields" => "slugs")) );		

				if( strlen( trim($url_video) ) > 0 ){
					if(strstr($url_video,'youtube.com') || strstr($url_video,'youtu.be')){
						$thumburl = array(get_thumbnail_video_src($url_video , 500 ,320));
						$item_class = "thumb-video youtube-fancy";
					}
					if(strstr($url_video,'vimeo.com')){
						$thumburl = array(wp_parse_thumbnail_vimeo(wp_parse_vimeo_link($url_video), 500, 320));	
						$item_class = "thumb-video vimeo-fancy";
					}
					$light_box_url = $url_video;
				}else{
					$thumb=get_post_thumbnail_id($post->ID);
					$thumburl=wp_get_attachment_image_src($thumb,'full');
					$item_class = "thumb-image";
					$light_box_url = $thumburl[0];
				}
									
				$portfolio_slider = get_post_meta($post->ID,THEME_SLUG.'_portfolio_slider',true);
				$portfolio_slider = unserialize($portfolio_slider);
				$slider_thumb = false;
				if( is_array($portfolio_slider) && count($portfolio_slider) > 0 ){
					$slider_thumb = true;
					$item_class = "thumb-slider";
				}

									
				?>
				<li class="item <?php echo $span_class ?><?php if( $i == 0 || $i % $columns == 0 ) echo ' first';?><?php if( $i == $num_count-1 || $i % $columns == $columns-1 ) echo ' last';?>">
					<div>
						<div class="image <?php if(!$thumbnail) echo "hidden-element"?>">
							<?php if($slider_thumb){?>
								<div class="portfolio-slider">
									<ul class="slides">
										<?php foreach( $portfolio_slider as $slide ){ ?>	
											<li><a href="<?php echo $slide['url'];?>"><img src="<?php echo print_thumbnail($slide['image_url'],true,$post_title,500,400,'',false,true); ?>" alt="<?php echo $slide['alt'];?>" title="<?php echo $slide['title'];?>" /></a></li>
										<?php } ?>
									</ul>	
								</div>				
							<?php }else{ ?>
								<?php 
									the_post_thumbnail('blog_shortcode',array('class' => 'thumbnail-effect-1'));
								?>
							<?php }?>
						</div>
						<div class="detail<?php if(!$thumb) echo ' noimage';?>">
							<p class="title <?php if(!$title) echo "hidden-element"?>"><h4 class="heading-title"><a href="<?php echo get_permalink($post->ID); ?>" class="wpt_title"  ><?php echo get_the_title($post->ID); ?></a></h4></p>
							<p class="excerpt <?php if(!$excerpt) echo "hidden-element"?>"><?php the_excerpt_max_words($excerpt_words); ?></p>
							<div class="meta <?php if(!$meta) echo "hidden-element"?>"><span class="author-time"><span class="author"><?php the_author(); ?></span><b>/</b><span class="time"><?php the_time('F d,Y'); ?></span><b>/</b><span class="comment-number"><?php comments_number( '0 Comments','1 Comment','% Comments'); ?></span></span></div>
						</div>	
					</div>
				</li>
		<?php
			$i++;
			}
			echo '</ul>';
			$ret_html = ob_get_contents();
			ob_end_clean();
		endif;
		wp_reset_query();
		return $ret_html;
	}
} 
//add_shortcode('recent_portfolios','recent_portfolios_function');



if(!function_exists ('recent_works_function')){
	function recent_works_function($atts,$content = false){
		extract(shortcode_atts(array(
			'count_items'	=>	'10'
			,'show_items' => 4
			,'gallery' => ''
		),$atts));

		wp_reset_query();	
		
			$args = array(
				'post_type' => 'portfolio'
				,'showposts' => $count_items

			);	
			
		if( strlen($gallery) > 0 ){
			$args = array(
				'post_type' => 'portfolio'
				,'showposts' => $count_items
				,'tax_query' => array(
					array(
						'taxonomy' => 'gallery'
						,'field' => 'slug'
						,'terms' => $gallery
					)
				)
			);
		}
		$num_count = count(query_posts($args));	
		if( have_posts() ) :
			$id_widget = 'recent-works-shortcode'.rand(0,1000);
			ob_start();
?>			
			<div id="<?php echo $id_widget;?>" class="shortcode-recent-works flexslider">
				<div class="slider-div">
					<ul class="slides">
					<?php			
						$i = 0;
						while(have_posts()) {
							the_post();
							global $post;
							?>
							<li class="item<?php if($i == 0) echo ' first';?><?php if(++$i == $num_count) echo ' last';?>">
								<?php
								$thumb_arr = get_thumbnail(300,170,'',get_the_title(),get_the_title());
								$thumb = $thumb_arr["fullpath"];
									$url_video = get_post_meta($post->ID,THEME_SLUG.'video_portfolio',true);
									if( strlen( trim($url_video) ) > 0 ){
										if(strstr($url_video,'youtube.com') || strstr($url_video,'youtu.be')){
											$thumburl = array(get_thumbnail_video_src($url_video , 500 ,500));
											$item_class = "thumb-video youtube-fancy";
										}
										if(strstr($url_video,'vimeo.com')){
											$thumburl = array(wp_parse_thumbnail_vimeo(wp_parse_vimeo_link($url_video), 500, 500));	
											$item_class = "thumb-video vimeo-fancy";
										}
										$light_box_url = $url_video;
									}else{
										$thumb=get_post_thumbnail_id($post->ID);
										$thumburl=wp_get_attachment_image_src($thumb,'full');
										$item_class = "thumb-image";
										$light_box_url = $thumburl[0];
									}								
								?>
								<div class="image <?php echo $id_widget?>">
									<a class="thumbnail" href="<?php the_permalink(); ?>"><?php echo print_thumbnail($thumburl[0],true,$post_title,500,330); ?></a>
									<div class="icons">
										<div>
											<a class="zoom-gallery fancybox" title="<?php echo get_the_title(); ?>" rel="fancybox" href="<?php echo $light_box_url;?>"></a>
											<a class="link-gallery " title="<?php echo get_the_title();?>" href="<?php echo get_permalink();?>"></a>
										</div>
									</div>	
								</div>
								<div class="detail<?php if(!$thumb) echo ' noimage';?>">
									<p class="title"><h4 class="heading-title"><a  class="wpt_title" href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></h4></p>
									<span class="author-time"><span class="author"><?php the_author(); ?></span><b>/</b><span class="time"><?php the_time('F d,Y'); ?></span><b>/</b><span class="comment-number"><?php comments_number( '0 Comments','1 Comment','% Comments'); ?></span></span>
								</div>							
							</li>
					<?php } ?>
					</ul>
				</div>
			</div>
			<div id="<?php echo $id_widget;?>_clone" style="display:none;" class="shortcode-recent-works flexslider">
			</div>
			
			<script type="text/javascript">
				jQuery(document).ready(function() {
					flex_carousel_slide('#<?php echo $id_widget;?>',<?php echo $show_items?>);
				});
			</script>
			<?php			
			$ret_html = ob_get_contents();
			ob_end_clean();
			//ob_end_flush();
		endif;
		wp_reset_query();
		return $ret_html;
	}
} 
//add_shortcode('recent_works','recent_works_function');

?>