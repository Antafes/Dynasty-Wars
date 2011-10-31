ALTER TABLE `dw_tribunal` ADD `deleted` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `dw_tribunal` CHANGE `decision` `decision` ENUM( 'nocent', 'innocent', 'rejected', 'other' ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ;
ALTER TABLE `dw_tribunal` ADD `deleted_by` INT UNSIGNED NOT NULL AFTER `deleted` ;
ALTER TABLE `dw_tribunal` ADD `block_comments` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `decision_date` ;
ALTER TABLE `dw_tribunal` CHANGE `decision` `decision` ENUM( 'nocent', 'innocent', 'rejected', 'other' ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT 'nocent';