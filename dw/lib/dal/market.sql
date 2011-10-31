 CREATE TABLE `dw_market` (
`mid` INT( 255 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`sid` INT( 255 ) NOT NULL ,
`s_resource` VARCHAR( 255 ) NOT NULL ,
`s_amount` FLOAT NOT NULL ,
`e_resource` VARCHAR( 255 ) NOT NULL ,
`e_amount` FLOAT NOT NULL ,
`complete` BOOL NOT NULL
) ENGINE = MYISAM;

ALTER TABLE `dw_market` ADD `timestamp` INT UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `dw_market` ADD `bid` INT AFTER `sid` ;
ALTER TABLE `dw_market` ADD `tax` TINYINT AFTER `e_amount` ;
ALTER TABLE `dw_market` CHANGE `tax` `tax` INT UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `dw_market` ADD `city_x` INT NOT NULL ,
ADD `city_y` INT NOT NULL 
