-- Adminer 4.2.0-dev MySQL dump

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `created` date NOT NULL,
  `grade` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  CONSTRAINT `file_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `acronym` varchar(255) NOT NULL,
  `solver` int(11) NOT NULL,
  `created` date NOT NULL,
  `submitted` date NOT NULL,
  `grade` int(11) NOT NULL,
  `mark` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `project_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `project` (`id`, `subject_id`, `text`, `acronym`, `solver`, `created`, `submitted`, `grade`, `mark`) VALUES
(1,	1,	'Moj projekt',	'MPJ',	3,	'2015-04-18',	'2015-06-10',	0,	'');

DROP TABLE IF EXISTS `proj_us`;
CREATE TABLE `proj_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_subj_id` int(11) NOT NULL,
  `manager` int(11) DEFAULT NULL,
  `analytic` int(11) DEFAULT NULL,
  `designer` int(11) DEFAULT NULL,
  `programmer` int(11) DEFAULT NULL,
  `tester` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_subj_id` (`user_subj_id`),
  CONSTRAINT `proj_us_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `proj_us_ibfk_2` FOREIGN KEY (`user_subj_id`) REFERENCES `user_subj` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `proj_us` (`id`, `project_id`, `user_subj_id`, `manager`, `analytic`, `designer`, `programmer`, `tester`) VALUES
(1,	1,	1,	0,	NULL,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `role` (`id`, `role`) VALUES
(1,	'student'),
(2,	'ucitel'),
(3,	'lecturer');

DROP TABLE IF EXISTS `school_year`;
CREATE TABLE `school_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(100) NOT NULL,
  `term_start` date NOT NULL,
  `term_end` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `school_year` (`id`, `year`, `term_start`, `term_end`) VALUES
(1,	'2015/2014 ZS',	'2015-04-18',	'2015-07-10');

DROP TABLE IF EXISTS `subject`;
CREATE TABLE `subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_year_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `school_year_id` (`school_year_id`),
  CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `subject` (`id`, `school_year_id`, `subject`) VALUES
(1,	1,	'ZSI');

DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_subj_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `created` date NOT NULL,
  `submitted` date NOT NULL,
  `grade` int(11) NOT NULL,
  `numfiles` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_subj_id` (`user_subj_id`),
  CONSTRAINT `task_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `task_ibfk_2` FOREIGN KEY (`user_subj_id`) REFERENCES `user_subj` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `role_id`, `login`, `password`, `name`, `surname`, `email`) VALUES
(1,	1,	'student',	'student',	'meno',	'menomeno',	'student@student.sk'),
(2,	2,	'ucitel',	'ucitel',	'ucitel',	'priezvisko',	'email@email.com'),
(3,	3,	'lecturer',	'lecturer',	'lecturer',	'lecturer',	'lecturer@lecturer.com');

DROP TABLE IF EXISTS `user_subj`;
CREATE TABLE `user_subj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `user_subj_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `user_subj_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_subj` (`id`, `user_id`, `subject_id`) VALUES
(1,	1,	1);

-- 2015-04-18 13:48:09