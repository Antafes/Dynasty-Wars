ALTER TABLE `dw_build`
	ADD COLUMN `start_datetime` DATETIME NOT NULL AFTER `upgrade`,
	ADD COLUMN `end_datetime` DATETIME NOT NULL AFTER `starttime`;
/* next: use timestampt2datetime/build.php */
ALTER TABLE `dw_build`
	DROP COLUMN `starttime`,
	DROP COLUMN `endtime`;

ALTER TABLE `dw_build_unit`
	ADD COLUMN `start_datetime` DATETIME NOT NULL AFTER `count`,
	ADD COLUMN `end_datetime` DATETIME NOT NULL AFTER `starttime`;
/* next: use timestampt2datetime/build_unit.php */
ALTER TABLE `dw_build_unit`
	DROP COLUMN `starttime`,
	DROP COLUMN `endtime`;

ALTER TABLE `dw_clan_applications`
	add COLUMN `create_datetime` DATETIME NOT NULL AFTER `applicationtext`;
/* next: use timestampt2datetime/clan_applications.php */
ALTER TABLE `dw_clan_applications`
	DROP COLUMN `apptime`;

ALTER TABLE `dw_log`
	ADD COLUMN `log_datetime` DATETIME NOT NULL AFTER `date`;
/* next: use timestamp2datetime/log.php */
ALTER TABLE `dw_log`
	DROP COLUMN `date`;

ALTER TABLE `dw_lostpw`
	ADD COLUMN `sent_datetime` DATETIME NOT NULL AFTER `mailid`;
/* next: use timestamp2datetime/lost_pw.php */
ALTER TABLE `dw_lostpw`
	DROP COLUMN `sent_time`;

ALTER TABLE `dw_market`
	ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `timestamp`;
/* next: use timestamp2datetime/market.php */
ALTER TABLE `dw_market`
	DROP COLUMN `timestamp`;

ALTER TABLE `dw_message`
	ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date`,
	ADD COLUMN `read_datetime` DATETIME NOT NULL AFTER `date_read`;
/* next: use timestamp2datetime/message.php */
ALTER TABLE `dw_message`
	DROP COLUMN `date`,
	DROP COLUMN `date_read`;

ALTER TABLE `dw_news`
	ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date`,
	ADD COLUMN `changed_datetime` DATETIME NOT NULL AFTER `last_changed`;
/* next: use timestamp2datetime/news.php */
ALTER TABLE `dw_message`
	DROP COLUMN `date`,
	DROP COLUMN `last_changed`;

ALTER TABLE `dw_res`
	ADD COLUMN `last_datetime` DATETIME NOT NULL AFTER `last_time`;
/* next: use timestamp2datetime/resources.php */
ALTER TABLE `dw_market`
	DROP COLUMN `last_time`;

ALTER TABLE `dw_research`
	ALTER `starttime` DROP DEFAULT;
ALTER TABLE `dw_research`
	CHANGE COLUMN `starttime` `end_datetime` DATETIME NOT NULL AFTER `lvl`;

ALTER TABLE `dw_tribunal`
	ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date`,
	ADD COLUMN `decision_datetime` DATETIME NOT NULL AFTER `decision_date`;
/* next: use timestamp2datetime/tribunal.php */
ALTER TABLE `dw_tribunal`
	DROP COLUMN `date`,
	DROP COLUMN `decision_date`;

ALTER TABLE `dw_tribunal_arguments`
	ADD COLUMN `added_datetime` DATETIME NOT NULL AFTER `date_added`;
/* next: use timestamp2datetime/tribunal_arguments.php */
ALTER TABLE `dw_tribunal_arguments`
	DROP COLUMN `date_added`;

ALTER TABLE `dw_tribunal_comments`
	ADD COLUMN `create_datetime` DATETIME NOT NULL AFTER `date_added`,
	ADD COLUMN `changed_datetime` DATETIME NOT NULL AFTER `date_last_changed`;
/* next: use timestamp2datetime/tribunal_comments.php */
ALTER TABLE `dw_tribunal_comments`
	DROP COLUMN `date_added`,
	DROP COLUMN `date_last_changed`;

ALTER TABLE `dw_troops_move`
	ADD COLUMN `end_datetime` DATETIME NOT NULL AFTER `endtime`;
/* next: use timestamp2datetime/troops_move.php */
ALTER TABLE `dw_troops_move`
	DROP COLUMN `endtime`;
	
/* next: use timestamp2datetime/users.php */