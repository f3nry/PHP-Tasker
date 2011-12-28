#!!!= UP

CREATE TABLE IF NOT EXISTS `test` (
  `id` INTEGER AUTO_INCREMENT,
  `name` VARCHAR(255),
  PRIMARY KEY(`id`)
);

#!!!= DOWN

DROP TABLE `test`;
