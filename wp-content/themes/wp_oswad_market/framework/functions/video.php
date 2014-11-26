<?php 
/*
	Get id video on daily motion
	Input : 
		- string $daily_link : the link of video on dailymotion. Examlple : http://www.dailymotion.com/video/xpc9t6_destructoid-talks-to-tony-hawk
	Output :
		- id of video on dailymotion. Example : xpc9t6_destructoid-talks-to-tony-hawk
*/
if(!function_exists ('wp_parse_daily_link')){
	function wp_parse_daily_link($daily_link){
		if (preg_match('/dailymotion.com\/video\/(.*)/', $daily_link, $match)) {
			return $match[1];
		}
		else{
			return substr($video_url,10,strlen($video_url));
		}
	}
}

/*
	Get id video on youtube
	Input : 
		- string $youtube_link : the link of video on youtube. Examlple : http://youtu.be/HsBrReP4W1Y
	Output :
		- id of video on dailymotion. Example : HsBrReP4W1Y
*/
if(!function_exists ('wp_parse_youtube_link')){
	function wp_parse_youtube_link($youtube_link){
		preg_match('%(?:youtube\.com/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_link, $match);
		if(count($match) >= 2)
			return $match[1];
	   else
		   return '';
	}
}

/*
	Get id video on vimeo
	Input : 
		- string $video_url : the link of video on vimeo. Examlple : http://vimeo.com/36739284
	Output :
		- id of video on dailymotion. Example : 36739284
*/
if(!function_exists ('wp_parse_vimeo_link')){
	function wp_parse_vimeo_link($video_url){
		// if (preg_match('~^http://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $video_url, $match)) {
			// return $match[1];
		// }
		// else{
			// return substr($video_url,10,strlen($video_url));
		// }
	}
}

/*
	Embed video from youtube, vimeo and dailymotion.
	Input :
		- string $video_url : the link of video from youtube, vimeo and dailymotion.
		- int $width : the width of embed video area.
		- int $height : the height of embed video area.
	Ouput :
		the html of embed.
*/
if(!function_exists ('get_embbed_daily')){
	function get_embbed_daily($video_url,$width = 940,$height = 558){
		// $url_embbed = "http://www.dailymotion.com/swf/".wp_parse_daily_link($video_url);
		// return '<object width="'.$width.'" height="'.$height.'">
					// <param name="movie" value="'.$url_embbed.'"></param>
					// <param name="allowFullScreen" value="true"></param>
					// <param name="allowScriptAccess" value="always"></param>
					// <embed type="application/x-shockwave-flash" src="'.$url_embbed.'" width="'.$width.'" height="'.$height.'" allowfullscreen="true" allowscriptaccess="always"></embed>
				// </object>';
	}
}

/*
	Get thumbnail image of video on vimeo
	Input : 
		- string $vimeo_id : id of video.
		- int $width : the width of thumbnail.
		- int $height : the height of thumbnail.
	Output :
		the link of thumbnail image.
*/
if(!function_exists ('wp_parse_thumbnail_vimeo')){
	function wp_parse_thumbnail_vimeo($vimeo_id,$width,$height)
	{
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/$vimeo_id.php");
		// curl_setopt($ch, CURLOPT_HEADER, 0);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// $output = unserialize(curl_exec($ch));
		// $output = $output[0];
		// curl_close($ch);
		// if($width >= 300 || $height >= 260)
			// return $output['thumbnail_large'];
		// else
			// return $output['thumbnail_medium'];
	}
}

/*
	Get thumbnail image of video
	Input : 
		- string $video_url : the link of video.
		- int $width : the width of thumbnail.
		- int $height : the height of thumbnail.
	Output :
		Image tag.
*/
if(!function_exists ('get_thumbnail_video')){
	function get_thumbnail_video($video_url,$width,$height){
		// if(strstr($video_url,'youtube.com') || strstr($video_url,'youtu.be')){
			// if($width >= 300 || $height >= 260)
				// return '<img width="'.$width.'" height="'.$height.'" src="http://img.youtube.com/vi/'.wp_parse_youtube_link($video_url).'/0.jpg"/>';
			// else
				// return '<img width="'.$width.'" height="'.$height.'" src="http://img.youtube.com/vi/'.wp_parse_youtube_link($video_url).'/1.jpg"/>';
		// }
		// else if(strstr($video_url,'vimeo.com')){
			// return '<img width="'.$width.'" height="'.$height.'" src="'.wp_parse_thumbnail_vimeo(wp_parse_vimeo_link($video_url),$width,$height).'" />';
		// }

	}
}

/*
	Get thumbnail image of video
	Input : 
		- string $video_url : the link of video.
		- int $width : the width of thumbnail.
		- int $height : the height of thumbnail.
	Output :
		the link of thumbnail image.
*/
if(!function_exists ('get_thumbnail_video_src')){
	function get_thumbnail_video_src($video_url,$width,$height){
		// if(strstr($video_url,'youtube.com') || strstr($video_url,'youtu.be')){
			// if($width >= 300 || $height >= 260)
				// return 'http://img.youtube.com/vi/'.wp_parse_youtube_link($video_url).'/0.jpg';
			// else
				// return 'http://img.youtube.com/vi/'.wp_parse_youtube_link($video_url).'/1.jpg';
		// }
		// else if(strstr($video_url,'vimeo.com')){
			// return wp_parse_thumbnail_vimeo(wp_parse_vimeo_link($video_url),$width,$height);
		// }

	}
}

/*
	Get embed of video on vimeo.
	Input : 
		- string $video_url : the link of video.
		- int $width : the width of embed area.
		- int $height : the height of embed area.
	Output :
		html of embed area.
*/
if(!function_exists ('get_embbed_vimeo')){
	function get_embbed_vimeo($video_url,$width,$height){
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/".wp_parse_vimeo_link($video_url).".php");
		// curl_setopt($ch, CURLOPT_HEADER, 0);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// $hash = unserialize(curl_exec($ch));
		// return '<iframe id="vimeo-iframe" src="http://player.vimeo.com/video/'.$hash[0]['id'].'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe><p><a href="http://vimeo.com/7235817">Zimoun : Compilation Video V2.8 | Sound Sculptures & Installations</a> from <a href="'.$hash[0]['user_url'].'">ZIMOUN</a> on <a href="http://vimeo.com">Vimeo</a>.</p>';
	}
}

/*
	Get embed of video.
	Input : 
		- string $video_url : the link of video.
		- int $width : the width of embed area.
		- int $height : the height of embed area.
	Output :
		html of embed area.
*/
if(!function_exists ('get_embbed_video')){
	function get_embbed_video($video_url,$width = 940,$height = 558){
		// if(strstr($video_url,'youtube.com') || strstr($video_url,'youtu.be')){
			// return "<iframe id='youtube-iframe' title='YouTube video player' class='youtube-player' type='text/html'
							// width='{$width}' height='{$height}' src='http://www.youtube.com/embed/".wp_parse_youtube_link($video_url)."?wmode=transparent'
			// frameborder='0' allowFullScreen></iframe>";
						
		// }
		// elseif(strstr($video_url,'vimeo.com')){
			// return get_embbed_vimeo($video_url,$width,$height);
		// }
		// elseif(strstr($video_url,'dailymotion.com')){
			// return get_embbed_daily($video_url,$width,$height);
		// }	
	}
}	
?>