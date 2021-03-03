<?php
/*
 * Plugin Name: Custom post-comments
 * Description: Plugin for comments in custom post type with shortcode.
 * Author: Maxim Rudenko
 */
require_once __DIR__.'/mindk_post_comments_db.php';
require_once __DIR__.'/mindk_post_comments_ajax.php';
require_once __DIR__.'/mindk_post_comments_shortcode.php';

register_activation_hook(__FILE__, 'mindk_post_comments_create_table');
add_action( 'init', 'mindk_post_comments' );
add_action('wp_footer', 'mindk_post_comments_scripts');
add_action('wp_ajax_mindk_post_comments', 'mindk_post_comments_ajax');
add_filter('manage_post_comments_posts_columns', 'mindk_post_comments_columns');
add_action('manage_post_comments_posts_custom_column', 'mindk_post_comments_table_content', 10, 2 );
add_shortcode('post_comments', 'mindk_post_comments_shortcode');


add_action( 'edit_form_after_title', function($post) {
    if ($post->post_type = 'post_comments') {
        wp_enqueue_style('mind-post-comments', plugins_url('css/mindk-post-comments.css', __FILE__));
        echo '<span class="post-comments-shortcode">[post_comments id='.$post->ID.']</span>';
    }
} );

function mindk_post_comments_columns( $defaults ) {
    wp_enqueue_style('mind-post-comments', plugins_url('css/mindk-post-comments.css', __FILE__));
    $defaults['shortcode']  = 'Shortcode';
    return $defaults;
}

function mindk_post_comments_table_content( $column_name, $post_id ) {
    if ($column_name == 'shortcode') {
        echo  '<span class="post-comments-shortcode">[post_comments id='.$post_id.']</span>';
    }
}

function mindk_post_comments_scripts($hook) {
    wp_register_script('mindk-post-comments', plugins_url('js/mindk-post-comments.js', __FILE__), array('jquery'));
    wp_enqueue_script('mindk-post-comments');
    wp_localize_script('mindk-post-comments', 'mindk_ajax', array('url'=>admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('mindk_ajax')));
    wp_enqueue_style('mind-post-comments', plugins_url('css/mindk-post-comments.css', __FILE__));
}

function mindk_post_comments() {
    $labels = array(
        'name'               => _x( 'Posts-comments', 'post type general name' ),
        'singular_name'      => _x( 'Post-comments', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'post-comments' ),
        'add_new_item'       => __( 'Add New post-comment' ),
        'edit_item'          => __( 'Edit Post-comment' ),
        'new_item'           => __( 'New Post-comments' ),
        'all_items'          => __( 'All Post-comments' ),
        'view_item'          => __( 'View Post-comments' ),
        'search_items'       => __( 'Search Post-comments' ),
        'not_found'          => __( 'No post-comments found' ),
        'not_found_in_trash' => __( 'No post-comments found in the Trash' ),
        'parent_item_colon'  => ’,
        'menu_name'          => 'Post-comments'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Custom post-comments',
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title' ),
        'has_archive'   => true,
        'taxonomies'    => array('post-comments'),
        'menu_icon'     => 'dashicons-format-chat'
    );
    register_post_type( 'post_comments', $args );
}

