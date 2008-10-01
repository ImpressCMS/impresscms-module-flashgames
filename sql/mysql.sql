# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `flashgames_cat`
#

CREATE TABLE flashgames_cat (
  cid int(5) unsigned NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  imgurl varchar(150) NOT NULL default '',
  PRIMARY KEY  (cid),
  KEY pid (pid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `flashgames_games`
#

CREATE TABLE flashgames_games (
  lid int(11) unsigned NOT NULL auto_increment,
  cid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  ext varchar(10) NOT NULL default '',
  res_x int(11) NOT NULL default '',
  res_y int(11) NOT NULL default '',
  bgcolor varchar(6) NOT NULL default 'FFFFFF',
  submitter int(11) unsigned NOT NULL default '0',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  hits int(11) unsigned NOT NULL default '0',
  rating double(6,4) NOT NULL default '0.0000',
  votes int(11) unsigned NOT NULL default '0',
  comments int(11) unsigned NOT NULL default '0',
  gametype tinyint(2) NOT NULL default '0',
  license tinytext NOT NULL,
  classfile varchar(100) default NULL,
  members INT default 0 NOT NULL,
  PRIMARY KEY  (lid),
  KEY cid (cid),
  KEY status (status),
  KEY title (title(40))
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `flashgames_text`
#

CREATE TABLE flashgames_text (
  lid int(11) unsigned NOT NULL default '0',
  description text NOT NULL,
  KEY lid (lid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `flashgames_votedata`
#

CREATE TABLE flashgames_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) unsigned NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
) TYPE=MyISAM;

#
# Table structure for table `flashgames_comments`
#

CREATE TABLE flashgames_comments (
  comment_id int(8) unsigned NOT NULL auto_increment,
  pid int(8) unsigned NOT NULL default '0',
  item_id int(8) unsigned NOT NULL default '0',
  date int(10) unsigned NOT NULL default '0',
  user_id int(5) unsigned NOT NULL default '0',
  ip varchar(15) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  comment text NOT NULL,
  nohtml tinyint(1) unsigned NOT NULL default '0',
  nosmiley tinyint(1) unsigned NOT NULL default '0',
  noxcode tinyint(1) unsigned NOT NULL default '0',
  icon varchar(25) NOT NULL default '',
  PRIMARY KEY  (comment_id),
  KEY pid (pid),
  KEY item_id (item_id),
  KEY user_id (user_id),
  KEY subject (subject(40))
) TYPE=MyISAM;


#
# Tablestructure for Table `xoops_flashgames_score`
# 

CREATE TABLE flashgames_score (
  lid int(11) unsigned NOT NULL default '0',
  name varchar(20) NOT NULL default '',
  score double NOT NULL default '0',
  ip varchar(15) default NULL,
  date timestamp(14) NOT NULL,
  playTime BIGINT NOT NULL,
  PRIMARY KEY  (lid,name)
) TYPE=MyISAM;


#
# Table structure for savedGames table xoops_flashgames_savedGames
#

CREATE TABLE flashgames_savedGames (
  lid int(11) unsigned NOT NULL default '0',
  name varchar(20) NOT NULL default '',
  gamedata text NOT NULL default '',
  date timestamp(14) NOT NULL,
  PRIMARY KEY (lid, name)
) TYPE=MyISAM;

