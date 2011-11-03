CREATE TABLE  `dw_languages` (
`language_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`language` CHAR( 2 ) NOT NULL ,
`active` TINYINT( 1 ) NOT NULL,
`fallback` TINYINT( 1 ) NOT NULL
) ENGINE = MYISAM;
INSERT INTO  `dw_languages` (
`language_id` ,
`language` ,
`active` ,
`fallback`
)
VALUES (
NULL ,  'de',  '1',  '1'
), (
NULL ,  'en',  '1',  '0'
);

ALTER TABLE  `dw_user` ADD  `registration_datetime` DATETIME NOT NULL AFTER  `regdate`;
ALTER TABLE  `dw_user` ADD  `last_login_datetime` DATETIME NOT NULL AFTER  `last_login`;