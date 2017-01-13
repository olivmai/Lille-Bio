drop database if exists lillebio;

create database lillebio;

use lillebio;

create table restaurant
	(
		numRest int(3),
		nomRest varchar(50) not null,
		rueRest varchar(100) not null,
		cpRest int(5) not null,
		villeRest varchar(45) not null,
		telRest char(10) not null,
		emailRest varchar(50) not null,
		etoileRest tinyint(1) not null,
		nbreCouvertRest tinyint(3) not null,
		nbreTableRest tinyint(2) not null,
		catRest varchar(30) not null,
		formRepasRest varchar(7) not null,
		constraint pk_restaurant primary key(numRest),
		contraint un_nomRest unique(nomRest)
	)

