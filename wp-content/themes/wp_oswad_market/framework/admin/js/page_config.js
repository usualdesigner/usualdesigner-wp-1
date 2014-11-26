jQuery(document).ready(function(){
	/* attach event to  page attributes changed */ 
    if(jQuery("select#page_template").length > 0 ){
        jQuery( "select#page_template" ).on( "change", function() {
            if( jQuery.trim(jQuery( this ).find('option:selected').html()) == 'Portfolio Template'){
                jQuery("#page_config li.hide_portfolio").hide();
            } else {
                jQuery("#page_config li.hide_portfolio").show();
            }
        });
    }
	/* attach event to  page attributes changed */ 
    if(jQuery("select#page_template").length > 0 ){
		var old_option = jQuery.trim(jQuery( 'select#page_template' ).find('option:selected').html());
		if( old_option == 'Contact Template' || old_option == 'Fullwidth Template' || old_option == 'Sitemap Template' || old_option == 'Archive Template'){
			jQuery("#page_config").hide();
		}
		if( old_option == 'Blog Template' || old_option == 'Portfolio Template'){
			jQuery("li.sub_layout").hide();
		}
        jQuery( "select#page_template" ).on( "change", function() {
			var option = jQuery.trim(jQuery( this ).find('option:selected').html());
            if( option == 'Contact Template' || option == 'Fullwidth Template' || option == 'Sitemap Template' || option == 'Archive Template'){
                jQuery("#page_config").hide();
            } else if( option == 'Blog Template' || option == 'Portfolio Template'){
				jQuery("#page_config").show();
				jQuery("li.sub_layout").hide();
			}
			else {
                jQuery("#page_config").show();
				jQuery("li.sub_layout").show();
            }
			 
        });
    }
	if(jQuery("select#page_layout").length > 0 ){
        jQuery( "select#page_layout" ).on( "change", function() {
            if( jQuery.trim(jQuery( this ).find('option:selected').html()) == 'Wide'){
				var option = jQuery.trim(jQuery( "select#page_template" ).find('option:selected').html());
				if( option != 'Blog Template' && option != 'Portfolio Template'){
					jQuery("li.sub_layout select").each(function(index, element){
                        jQuery(element).val(jQuery(element).find("option").eq(0).val());
                    });
					jQuery("li.sub_layout").show();
				}
            } else {
                jQuery("li.sub_layout").hide();
            }
        });
    }
	
	
});