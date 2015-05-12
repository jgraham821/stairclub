CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL auto_increment,
  `username` char(64) NOT NULL,
  `firstname` char(64) NULL,
  `lastname` char(64) NULL,
  `email` char(64) NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `result` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
   PRIMARY KEY  (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;