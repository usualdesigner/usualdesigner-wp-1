<?php 
if(!function_exists ('ew_img_video')){
	function ew_img_video($atts){
		extract(shortcode_atts(array(
			'src_thumb'		=> 	'',
			'src_zoom_img' 	=> 	'',
			'link_video'	=>	'',
			'width_thumb' 	=> 	'190',
			'height_thumb' 	=> 	'103',
			'type'			=>	'',
			'use_lightbox'	=>	'true',
			'custom_link'	=>	'#',
			'class'			=>	'',
			'title'			=>	''
		),$atts));
		$width_div = $width_thumb + 8;
		$height_div = $height_thumb + 8;
		$left_fancy = floor(($width_div - 30)/2);
		$top_fancy = floor(($height_div - 30)/2);
		$result = "<div class='image-style {$class}' style='width:{$width_div}px;height:{$height_div}px'>";
		
		if($type == 'video'){
			if($link_video){
				if(strstr($link_video,'youtube.com') || strstr($link_video,'youtu.be')){
					 $class_fancy = ' youtube';
					 $big_video_url = 'http://www.youtube.com/watch?v='.  wp_parse_youtube_link($link_video);
				}
				else{
					$class_fancy = 'vimeo';
					$big_video_url = $link_video;
				}
				$result .= "<a class='thumbnail' href='".$custom_link."' style='width:{$width_thumb}px;height:{$height_thumb}px'>".get_thumbnail_video($link_video,$width_thumb,$height_thumb)."</a>";
				if($use_lightbox == 'true')
					$result .= "<div class='fancybox_container' style='display:none;' id='img-video-".rand(0,1000)."{$width_thumb}{$height_thumb}'>
							<a title='{$title}' class='fancybox_control {$class_fancy}' href='{$big_video_url}' style='left:{$left_fancy}px;top:{$top_fancy}px'>Lightbox</a>
						</div>";
			}
		}
		else {
			if($src_thumb)
				$result .= "<a href='{$custom_link}' class='thumbnail' style='width:{$width_thumb}px;height:{$height_thumb}px'><img width='{$width_thumb}' height='{$height_thumb}' src='{$src_thumb}'/></a>";
			if($src_zoom_img && $use_lightbox == 'true')	
				$result .= "<div class='fancybox_container' style='display:none;' id='img-video-".rand(0,1000)."{$width_thumb}{$height_thumb}'>
						<a title='{$title}' class='fancybox_control' href='{$src_zoom_img}' style='left:{$left_fancy}px;top:{$top_fancy}px'>Lightbox</a>
					</div>";
		}
		
		$result .= "</div>";
		
		return $result;
	}
}
add_shortcode('ew_img_video','ew_img_video');
?>