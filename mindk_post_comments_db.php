<?php

function mindk_post_comments_create_table() {
    global $wpdb;
    $query = "CREATE TABLE IF NOT EXISTS `mindk_post_comments` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `post_id` bigint(20) UNSIGNED NOT NULL,
        `author_name` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `author_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `author_ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
        `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        PRIMARY KEY (`id`),
        KEY `post_id` (`post_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
    if (!$wpdb->query($query) ) {
        die('Error of creating table.');
    }
}
