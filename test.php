<?php
	$sql = " DROP TABLE IF EXISTS `inventory`;";
	$sql .= " CREATE TABLE `inventory` (" .
			  "`id` mediumint(9) NOT NULL AUTO_INCREMENT," .
			  "`wine_id` mediumint(9) NOT NULL," .
			  "`datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP," .
			  "`quantity` int(11) NOT NULL," .
			  "`cost` decimal(10,2) DEFAULT NULL," .
			  "`sell_price` decimal(10,2) DEFAULT NULL," .
			  "`notes` tinyblob," .
			  "PRIMARY KEY (`id`)" .
			") ENGINE=MyISAM AUTO_INCREMENT=5068 DEFAULT CHARSET=latin1;";
	$sql .= " DROP TABLE IF EXISTS `wine`;";
	$sql .= " CREATE TABLE `wine` (" .
			  "`id` mediumint(9) NOT NULL AUTO_INCREMENT," .
			  "`upc` varchar(20) ," .
			  "`type` varchar(50) NOT NULL," .
			  "`name` varchar(255) NOT NULL," .
			  "`vintage` varchar(4) NOT NULL," .
			  "`cost` decimal(10,2) DEFAULT NULL," .
			  "`sell_price` decimal(10,2) DEFAULT NULL," .
			  "PRIMARY KEY (`id`)" .
			") ENGINE=MyISAM AUTO_INCREMENT=4818 DEFAULT CHARSET=latin1;";
	$sql .= " DROP TABLE IF EXISTS `wine_types`;";
	$sql .= " CREATE TABLE `wine_types` (" .
			  "`id` int(11) NOT NULL AUTO_INCREMENT," .
			  "`name` varchar(50) DEFAULT NULL," .
			  "PRIMARY KEY (`id`)" .
			") ENGINE=MyISAM DEFAULT CHARSET=latin1;";
	$sql .= " DROP TABLE IF EXISTS `locations`;";
	$sql .= " CREATE TABLE IF NOT EXISTS `locations` (" .
			  "`id` int(11) NOT NULL AUTO_INCREMENT," .
			  "`locname` varchar(50) NOT NULL," .
			  "PRIMARY KEY (`id`)" .
			") ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;";
	$sql .= " INSERT INTO `locations` (`locname`) VALUES" .
			"('Cellar')," .
			"('Bar 1')," .
			"('Bar 2');";
	die($sql);