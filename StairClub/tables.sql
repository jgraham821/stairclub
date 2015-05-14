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
  `route_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `date` DATETIME NOT NULL,
   PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `route` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(64) NOT NULL,
  `start_tag` char(64) NULL,
  `end_tag` char(64) NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;