CREATE DATABASE formation;

USE formation;

CREATE TABLE IF NOT EXISTS `news` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`auteur` varchar(30) NOT NULL,
	`titre` varchar(100) NOT NULL,
	`contenu` text NOT NULL,
	`dateAjout` datetime NOT NULL,
	`dateModif` datetime NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `comments` (
	`id` mediumint(9) NOT NULL AUTO_INCREMENT,
	`news` smallint(6) NOT NULL,
	`auteur` varchar(50) NOT NULL,
	`contenu` text NOT NULL,
	`date` datetime NOT NULL,
	PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS T_MEM_memberc
(
	MMC_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	MMC_name VARCHAR(50) NOT NULL,
	MMC_surname VARCHAR(50) NOT NULL,
	MMC_login VARCHAR(50) NOT NULL,
	MMC_email VARCHAR(50) NOT NULL,
	MMC_password VARCHAR(200) NOT NULL,
	MMC_dateInscription DATETIME NOT NULL,
	MMC_dateBirth DATE NOT NULL
);