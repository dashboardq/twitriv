<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `restrictions` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `view_credits` int NOT NULL DEFAULT '0',
    `view_credits_used` int NOT NULL DEFAULT '0',
    `premium_level` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `view_credits_reset_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);
SQL;

    $db->query($sql);
};

// Down
$down = function($db) {
    $sql = <<<'SQL'
DROP TABLE `restrictions`;
SQL;

    $db->query($sql);
};
