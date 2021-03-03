<?php

function mindk_post_comments_view($comments) {
    ob_start();
    ?>
    <section class="comments">
        <?php foreach($comments as $comment):?>
            <div class="comment">
                <p class="comment-author">
                    <b><?=$comment['author_name']?></b><br>
                    <span><?=wp_date('j.m.Y H:i', strtotime($comment['date']))?></span>
                </p>
                <p class="comment-content"><?=$comment['content']?></p>
            </div>
        <?php endforeach ?>
    </section>
    <?php
    $result =  ob_get_clean();
    return $result;
}