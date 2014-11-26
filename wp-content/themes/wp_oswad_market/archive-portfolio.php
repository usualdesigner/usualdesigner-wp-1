<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Oswad Market
 * @since WD_Responsive
 */

get_header(); ?>
		<?php 
		$has_breadcrumb = true;
		$has_page_title = true;
		if( $has_breadcrumb || $has_page_title ){
			global $smof_data;
			$style = '';
			if( isset($smof_data['wd_bg_breadcrumbs']) && $smof_data['wd_bg_breadcrumbs'] != '' )
				$style = 'style="background: url('.$smof_data['wd_bg_breadcrumbs'].')"';
			echo '<div class="breadcrumb-title-wrapper"><div class="breadcrumb-title" '.$style.'>';
				if( $has_page_title )
					echo '<h1 class="heading-title page-title">'.__("Portfolio Archives","wpdance").'</h1>';
				if( $has_breadcrumb )
					wd_show_breadcrumbs();
			echo '</div></div>';
		}
		?>
		<div id="container" class="page-template archive-page archive-portfolio">			
				<div id="content" class="container" role="main">					
						<div id="container-main" class="col-sm-18" >
							<div class="main-content">
							
								<ul class="list-posts">
									<?php	
									global $query_string;$count=0;
									query_posts($query_string.'&posts_per_page='.get_option('posts_per_page').'&paged='.get_query_var('page'));
									if(have_posts()) : while(have_posts()) : the_post(); global $post;$count++;global $wp_query;
										$post_title = esc_attr(get_the_title($post->ID));
										$post_url =  esc_url(get_permalink($post->ID));
										$url_video = esc_url(get_post_meta($post->ID,THEME_SLUG.'video_portfolio',true));
										$proj_link = esc_url(get_post_meta($post->ID,THEME_SLUG.'proj_link',true));
										$term_list = implode( ' ', wp_get_post_terms($post->ID, 'gallery', array("fields" => "slugs")) );
										$thumburl = $thumb = '';
										
										if( strlen( trim($url_video) ) > 0 ){
											$thumb = get_post_thumbnail_id($post->ID);
											if(strstr($url_video,'youtube.com') || strstr($url_video,'youtu.be')){
												$thumb = get_post_thumbnail_id($post->ID);
												$thumburl = wp_get_attachment_image_src($thumb,'content_thumb','false');
												$item_class = "thumb-video youtube-fancy";
											}
											if(strstr($url_video,'vimeo.com')){
												$thumb = get_post_thumbnail_id($post->ID);
												$thumburl = wp_get_attachment_image_src($thumb,'content_thumb','false');												
												$item_class = "thumb-video vimeo-fancy";
											}
											$light_box_url = $url_video;
										}else{
											$thumb = get_post_thumbnail_id($post->ID);
											$thumburl = wp_get_attachment_image_src($thumb,'content_thumb','false');
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
										<?php 
											$sub_class = '';
											if($count==1) 
												$sub_class =  " first"; 
											if($count==$wp_query->post_count) 
												$sub_class =  " last"
										?>
										
										<li <?php post_class("home-features-item".$_sub_class);?>>
											<div class="post-info-thumbnail">
												
													<div class="thumbnail">
														<?php 
															 if( $slider_thumb ){?>	
																<div class="portfolio-slider">
																	<ul class="slides">
																		<?php foreach( $portfolio_slider as $slide ){ ?>	
																		<?php $_thumb_uri = wp_get_attachment_image_src( $slide['thumb_id'], 'content_thumb', false );
																			$_thumb_uri = $_thumb_uri[0];
																			$_sub_thumb_uri = wp_get_attachment_image_src( $slide['thumb_id'], 'content_thumb', false );
																			$_sub_thumb_uri = $_sub_thumb_uri[0]; 
																		?>
																			<li data-thumb="<?php  echo esc_url($_sub_thumb_uri); ?>"><a href="<?php echo esc_html($slide['url']);?>"><img alt="<?php echo esc_html($slide['alt']);?>" class="opacity_0" src="<?php echo  esc_url($_thumb_uri); ?>"/></a></li>
																		<?php } ?>
																	</ul>	
																</div>	
															<?php } else {
																?>
																<div class="image">
																	<a class="thumb-image" href="<?php the_permalink() ; ?>">
																	<?php 
																		if ( has_post_thumbnail() ) {
																			the_post_thumbnail('blog_thumb',array('class' => 'thumbnail-blog'));
																			//the_post_thumbnail('blog_thumb',array('class' => 'thumbnail-effect-2'));
																		} 			
																	?>	
																	<div class="thumbnail-shadow"></div>									
																	</a>
																	
																</div>
																<?php
															}
														?>	
													</div>
													
												
												<div class="post-info-meta">
													
														<div class="author">	
															<?php the_author_posts_link(); ?> 
														</div>
													
													
														<div class="time">
															<span class="entry-date"><?php echo get_the_date('M d, Y') ?></span><br/>
														</div>
													
													
														<span class="views-count">
															<?php ppbv_display_product(true); ?>
														</span>
													
													
														<span class="comments-count">
															<?php $comments_count = wp_count_comments($post->ID); if($comments_count->approved < 10 && $comments_count->approved > 0) echo '0'; echo $comments_count->approved; ?>
														</span>
													
												</div>
											</div><!-- end post info -->
											
											<div class="post-info-content">
												<?php if ( is_object_in_taxonomy( get_post_type(), 'gallery' ) ) : // Hide category text when not supported ?>
												<?php
													/* translators: used between list items, there is a space after the comma */
													$categories_list = get_the_category_list( __( ', ', 'wpdance' ) );
														if ( $categories_list ):
													?>
													<span class="cat-links">
														<?php printf( __( '<span class="%1$s"></span> %2$s', 'wpdance' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );?>
													</span>
													<?php endif; // End if categories ?>
												<?php endif; // End if is_object_in_taxonomy( get_post_type(), 'category' ) ?>
												<div class="post-title">
											
														<a class="post-title heading-title" href="<?php the_permalink() ; ?>"><h2 class="heading-title"><?php the_title(); ?></h2></a>
														<?php edit_post_link( __( 'Edit', 'wpdance' ), '<span class="wd-edit-link hidden-phone">', '</span>' ); ?>
														<div class="clear"></div>
														
												</div>
												<p class="short-content<?php if( $archive_page_config['show_excerpt_phone'] != 1 ) echo " hidden-phone";?>"><?php /*the_content();*/the_excerpt_max_words(160,$post); ?></p>	
												<?php wp_link_pages(); ?>
												<a class="read-more " href="<?php the_permalink() ; ?>"><span><span><?php _e('Read more','wpdance'); ?></span></span></a>	
											</div><!-- end post ... -->
										</li>
										<?php //if (($count >= 1) and($count < $wp_query->post_count)) echo "<li><div class='item-border alpha omega'></div></li>" ?>
									<?php						
									endwhile;
									else : echo "<div class='alpha omega'><div class='alert'>Sorry. There are no posts to display.</div></div>";
									endif;	
									wp_reset_query();
								?>	
								</ul>
							</div>
							<div class="clear"></div>
							<div class="end_content">
							   <div class="count_project"><span><?php echo wp_count_posts('portfolio')->publish; ?></span> <?php _e("Project","wpdance"); ?></div>
							   <div class="page_navi"><?php ew_pagination(); wp_reset_query();?></div>
							</div>
						</div>	
						<div id="right-sidebar" class="col-sm-6">
							<div class="right-sidebar-content">
								<?php
									if ( is_active_sidebar( 'blog-widget-area-right' ) ) : ?>
									<ul class="xoxo">
										<?php dynamic_sidebar( 'blog-widget-area-right' ); ?>
									</ul>
								<?php endif; ?>
							</div>
						</div>
						
				</div>
		</div>
<?php get_footer(); ?>
