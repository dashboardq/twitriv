<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `tweeters` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `twitter_id` varchar(255) NOT NULL DEFAULT '',
    `twitter_name` varchar(255) NOT NULL DEFAULT '',
    `twitter_username` varchar(255) NOT NULL DEFAULT '',
    `last_tweet_id` varchar(255) NOT NULL DEFAULT '',
    `last_tweet_at` timestamp NULL DEFAULT NULL,
    `last_reply_id` varchar(255) NOT NULL DEFAULT '',
    `last_reply_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);
SQL;

    $db->query($sql);
};

// Down
$down = function($db) {
    $sql = <<<'SQL'
DROP TABLE `tweeters`;
SQL;

    $db->query($sql);
};
