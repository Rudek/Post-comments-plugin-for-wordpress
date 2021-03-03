<?php
require_once __DIR__.'/views/mindk_post_comments_view.php';

function mindk_post_comments_ajax() {
    if ( !wp_verify_nonce( $_POST['security'], 'mindk_ajax' ) ) {
        wp_send_json(['status' => 'error', 'message' => 'Ошибка безопасности'], 422);
    }

    parse_str($_POST['formData'], $mindk_array);

    if ( empty($mindk_array['author_name']) ) {
        wp_send_json(['status' => 'error', 'message' => 'Заполните имя'], 422);
    }

    if ( empty($mindk_array['author_email']) ) {
        wp_send_json(['status' => 'error', 'message' => 'Заполните email'], 422);
    }

    if ( !is_email($mindk_array['author_email']) ) {
        wp_send_json(['status' => 'error', 'message' => 'Некоректный email'], 422);
    }
    $mindk_array['content'] = wp_strip_all_tags($mindk_array['content']);

    if ( empty($mindk_array['content']) ) {
        wp_send_json(['status' => 'error', 'message' => 'Пустой комментарий'],422);
    }

    global $wpdb;

    $post_comments_exists = (new WP_Query([
        'post_type' => 'post_comments',
        'p'=> intval($mindk_array['mindk_post_comments_id']),
        'post_status' => 'published'
    ]))->found_posts;

    if ( !$post_comments_exists ) {
        wp_send_json(['status' => 'error', 'message' => 'Пост отсутствует или не опубликован'], 404);
    }

    $resultQuery = $wpdb->query(
        $wpdb->prepare(
            'INSERT INTO mindk_post_comments (author_name, author_email, author_ip, content, post_id) VALUES ( %s, %s, %s, %s, %d)',
            $mindk_array['author_name'], $mindk_array['author_email'], $_SERVER['REMOTE_ADDR'], $mindk_array['content'], $mindk_array['mindk_post_comments_id'])
    );
    if ($resultQuery) {
        $mindk_array = $wpdb->get_row('SELECT * FROM mindk_post_comments WHERE id='.$wpdb->insert_id, ARRAY_A);
        wp_send_json(['status' => 'success', 'message' => 'Комментарий добавлен', 'body' => mindk_post_comments_view([$mindk_array])], 201);
    } else {
        wp_send_json((['status' => 'error', 'message' => 'Ошибка добавления комментария']));
    }
}