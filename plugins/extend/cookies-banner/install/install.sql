CREATE TABLE `sunlight_cookies_scripts` (
  `id` MEDIUMINT(9) AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) DEFAULT '',
  `code` TEXT DEFAULT '',
  `type` TINYINT DEFAULT NULL,
  `position` TINYINT DEFAULT 1,
  `published` TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE `sunlight_cookies_settings` (
  `id` TINYINT NOT NULL PRIMARY KEY CHECK (`id` = 1),
  `headline` VARCHAR(255) DEFAULT '',
  `btn_accept` VARCHAR(100) DEFAULT '',
  `btn_decline` VARCHAR(100) DEFAULT '',
  `btn_settings` VARCHAR(100) DEFAULT '',
  `text` TEXT DEFAULT '',
  `page_id` TINYINT DEFAULT 0
);