<?php
/**
 * Plugin Name: Custom Code of the Site
 * Plugin URI: http://bernackiy.name
 * Version: 1.0.0
 * Author: Aleksey Bernackiy
 * Author URI: http://bernackiy.name
 */

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function wpse103469_wc_price_per_unit_mb()
{

    add_meta_box(
        'wc_price_per_unit_mb',
        __('Price per Unit', __FILE__ . '_translation'),
        'wpse103469_wc_price_per_unit_inner_mb',
        'product',
        'advanced',
        'high'
    );
}

add_action('add_meta_boxes', 'wpse103469_wc_price_per_unit_mb');

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function wpse103469_wc_price_per_unit_inner_mb($post)
{

    // Add an nonce field so we can check for it later.
    wp_nonce_field('wpse103469_wc_price_per_unit_inner_mb', 'wpse103469_wc_price_per_unit_inner_mb_nonce');

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta($post->ID, 'wc_price_per_unit_key', true);

    echo '<label for="wc_price_per_unit_field">';
    _e("Price per Unit", __FILE__ . '_translation');
    echo '</label> ';
    echo '<select id="wc_price_per_unit_field" name="wc_price_per_unit_field">';
    foreach (include_once('values.php') as $_key => $_value) {
        echo '<option value="'.$_key.'" '.((esc_attr($value) == esc_attr($_key)) ? ' selected' : '').'>'.esc_attr($_value).'</option>';
    }
    echo '</select>';

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function wpse103469_wc_price_per_unit_save_postdata($post_id)
{

    /*
     * We need to verify this came from the our screen and with proper authorization,
     * because save_post can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['wpse103469_wc_price_per_unit_inner_mb_nonce']))
        return $post_id;

    $nonce = $_POST['wpse103469_wc_price_per_unit_inner_mb_nonce'];

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, 'wpse103469_wc_price_per_unit_inner_mb'))
        return $post_id;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    // Check the user's permissions.
    if ('page' == $_POST['post_type']) {

        if (!current_user_can('edit_page', $post_id))
            return $post_id;

    } else {

        if (!current_user_can('edit_post', $post_id))
            return $post_id;
    }

    /* OK, its safe for us to save the data now. */

    // Sanitize user input.
    $price_per_unit = sanitize_text_field($_POST['wc_price_per_unit_field']);

    // Update the meta field in the database.
    update_post_meta($post_id, 'wc_price_per_unit_key', $price_per_unit);
}

add_action('save_post', 'wpse103469_wc_price_per_unit_save_postdata');

add_filter('woocommerce_get_price_html', 'wpse103469_add_price_per_unit_meta_to_price');

function wpse103469_add_price_per_unit_meta_to_price($price)
{
    if (get_post_meta(get_the_ID(), 'wc_price_per_unit_key', true) && get_post_meta(get_the_ID(), 'wc_price_per_unit_key', true) != 'empty')
        $price .= ' за ' . get_post_meta(get_the_ID(), 'wc_price_per_unit_key', true);
    return $price;
}