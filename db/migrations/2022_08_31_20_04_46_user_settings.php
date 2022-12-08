<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
ALTER TABLE `user_settings`
  ADD COLUMN `home_replies` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `home_retweets` tinyint(1) NOT NULL DEFAULT '0',
  ADD COLUMN `twitter_base` varchar(255) NOT NULL DEFAULT 'https://twitter.com',
  ADD COLUMN `twitter_new_tab` tinyint(1) NOT NULL DEFAULT '0';
SQL;

    $db->query($sql);
};

// Down
$down = function($db) {
    $sql = <<<'SQL'
ALTER TABLE `user_settings`
  DROP COLUMN `home_replies`,
  DROP COLUMN `home_retweets`,
  DROP COLUMN `twitter_base`,
  DROP COLUMN `twitter_new_tab`;
SQL;

    $db->query($sql);
};
