<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `profiles` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `connection_id` bigint unsigned NOT NULL DEFAULT '0',
    `twitter_id` varchar(255) NOT NULL DEFAULT '',
    `twitter_name` varchar(255) NOT NULL DEFAULT '',
    `twitter_username` varchar(255) NOT NULL DEFAULT '',
    `twitter_profile_image_url` varchar(255) NOT NULL DEFAULT '',
    `twitter_verified` tinyint(1) NOT NULL DEFAULT '0',
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
DROP TABLE `profiles`;
SQL;

    $db->query($sql);
};
