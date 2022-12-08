<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
ALTER TABLE `password_resets`
  ADD COLUMN `created_ip` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `used_ip` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `used` tinyint(1) NOT NULL DEFAULT '0',
  ADD COLUMN `expires_at` timestamp NULL DEFAULT NULL;
SQL;

    $db->query($sql);
};

// Down
$down = function($db) {
    $sql = <<<'SQL'
ALTER TABLE `password_resets`
  DROP COLUMN `created_ip`,
  DROP COLUMN `used_ip`,
  DROP COLUMN `used`,
  DROP COLUMN `expires_at`;
SQL;

    $db->query($sql);
};
