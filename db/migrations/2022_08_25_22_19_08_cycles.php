<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `cycles` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `tweeter_id` bigint unsigned NOT NULL DEFAULT '0',
    `search_id` bigint unsigned NOT NULL DEFAULT '0',
    `name` varchar(255) NOT NULL DEFAULT '',
    `link` varchar(255) NOT NULL DEFAULT '',
    `type` varchar(255) NOT NULL DEFAULT '',
    `twitter_id` varchar(255) NOT NULL DEFAULT '',
    `twitter_name` varchar(255) NOT NULL DEFAULT '',
    `twitter_username` varchar(255) NOT NULL DEFAULT '',
    `last_tweet_id` varchar(255) NOT NULL DEFAULT '',
    `last_tweet_at` timestamp NULL DEFAULT NULL,
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
DROP TABLE `cycles`;
SQL;

    $db->query($sql);
};
