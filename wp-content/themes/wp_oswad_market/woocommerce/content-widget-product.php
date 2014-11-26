<?php global $product; ?>
<li>
	<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
		<?php echo $product->get_image(); ?>
		<?php echo $product->get_title(); ?>
	</a>
	<?php 
	if ( ! empty( $show_rating ) ){
		if( function_exists('wd_get_rating_html') ){
			echo wd_get_rating_html();
		}
		else{
			echo $product->get_rating_html(); 
		}
	}
	?>
	<?php echo $product->get_price_html(); ?>
</li>