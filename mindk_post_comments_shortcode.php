<?php

require_once __DIR__.'/views/mindk_post_comments_view.php';

function mindk_post_comments_shortcode($attrs) {
    global $wpdb;
    $comments = $wpdb->get_results(
        $wpdb->prepare('SELECT * FROM mindk_post_comments WHERE post_id = %d ORDER BY date DESC', $attrs['id']),
        'ARRAY_A'
    );
    ob_start();
    ?>
        <section class="mindk-comment-form">
            <form id="mindk_post_comment">
                <p>
                    <label>Имя</label>
                    <input name="author_name" type="text"/>
                </p>
                <p>
                    <label>Email</label>
                    <input name="author_email" type="email"/>
                </p>
                <p class="comment-form-comment">
                    <label for="mindk_comment">Комментарий</label>
                    <textarea id="mindk_comment" name="content" cols="45" rows="4" required></textarea>
                </p>
                <p>
                    <input type="hidden" name="mindk_post_comments_id" value="<?=$attrs['id']?>" />
                    <input type="submit" value="Отправить" />
                </p>
            </form>
            <?=mindk_post_comments_view($comments)?>
        </section>
    <?php
    return ob_get_clean();
}
