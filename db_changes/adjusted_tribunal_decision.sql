ALTER TABLE `dw_tribunal`
	CHANGE COLUMN `decision` `decision` ENUM('undue','nocent','innocent','rejected','other') NOT NULL DEFAULT 'nocent' COLLATE 'utf8_general_ci' AFTER `judge`;