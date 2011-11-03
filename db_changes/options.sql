ALTER TABLE  `dw_languages` ADD  `name` VARCHAR( 255 ) NOT NULL AFTER  `language`;
UPDATE  `dw_languages` SET  `name` =  'Deutsch' WHERE  `dw_languages`.`language_id` =1;
UPDATE  `dw_languages` SET  `name` =  'English' WHERE  `dw_languages`.`language_id` =2;