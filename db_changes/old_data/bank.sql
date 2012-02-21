 CREATE TABLE `dw`.`dw_clan_bank` (
`cid` INT( 255 ) NOT NULL ,
`uid` INT( 255 ) NOT NULL ,
`resource` VARCHAR( 5 ) NOT NULL ,
`amount` FLOAT NOT NULL
) ENGINE = MYISAM

ALTER TABLE `dw_clan_bank` ADD `forUid` INT( 255 ) NOT NULL AFTER `uid` ;