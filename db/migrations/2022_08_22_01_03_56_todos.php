<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `todos` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `tweet_id` varchar(255) NOT NULL DEFAULT '',
    `tweet_content` longtext,
    `status` varchar(255) NOT NULL DEFAULT 'open',
    `note` longtext,
    `search` longtext,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FULLTEXT (`search`)
);
SQL;

    $db->query($sql);
};

// Down
$down = function($db) {
    $sql = <<<'SQL'
DROP TABLE `todos`;
SQL;

    $db->query($sql);
};
