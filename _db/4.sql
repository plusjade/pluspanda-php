

ALTER TABLE  `sites` DROP INDEX  `url`;
ALTER TABLE  `sites` ADD UNIQUE (
`apikey`
);

UPDATE  `pluspanda`.`version` SET  `at` =  '4';