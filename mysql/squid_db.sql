# SAMS - SQUID - DB MySQL-Dump
# --------------------------------------------------------
#GRANT ALL ON squidlog.* TO squid@localhost IDENTIFIED BY "redir";

DROP DATABASE IF EXISTS squidlog;
CREATE DATABASE squidlog;

USE squidlog;
#
# ��������� ������� `cache`
#
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `date` date,
  `time` time,
  `user` char(25),
  `domain` char(25),
  `size` BIGINT UNSIGNED NULL,
  `ipaddr` char(15) NOT NULL,
  `period` BIGINT UNSIGNED NULL,
  `url` char(100) NOT NULL,
  `hit` BIGINT UNSIGNED NULL,
  `method` char(15) NOT NULL
) ENGINE=MyISAM;
   
ALTER TABLE cache ADD INDEX (date);
ALTER TABLE cache ADD INDEX (user);
ALTER TABLE cache ADD INDEX (domain);

DROP TABLE IF EXISTS cachesum;
CREATE TABLE cachesum (
  date date NOT NULL default '0000-00-00',
  user varchar(25) NOT NULL default '',
  domain varchar(25) NOT NULL default '',
  size bigint(20) unsigned default NULL,
  hit bigint(20) unsigned default NULL,
  PRIMARY KEY  (date,user,domain)
) ENGINE=MyISAM;

insert into cachesum select date,user,domain,sum(size),sum(hit) from cache group by date,user;

DROP TABLE IF EXISTS files;
CREATE TABLE files (
  id tinyint(3) NOT NULL default '0',
  filepath varchar(50) NOT NULL default '',
  url varchar(120) NOT NULL default '',
  size int(12) NOT NULL default '0'
) ENGINE=MyISAM;


