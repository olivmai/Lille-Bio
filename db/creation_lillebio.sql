drop database if exists lillebio;

create database lillebio;

use lillebio;

create table Image
	(
		numImg int(3) AUTO_INCREMENT,
		nomImg varchar(25) not null,
		urlImg varchar(50) not null,
		constraint pk_image primary key(numImg)
	 );
create table Menu
	(
		numMenu int(3) AUTO_INCREMENT,
		nomMenu varchar(30) not null,
		prixMenu float(4,2) not null,
		constraint pk_menu primary key(numMenu)
	);
create table Client
	(
		numClient int(4) AUTO_INCREMENT,
		nomClient varchar(50) not null,
		prenomClient varchar(50) not null,
		rueClient varchar(200) null,
		villeClient varchar(50) null,
		cpClient int(5) null,
		telClient int(10) not null,
		emailClient varchar(50) not null,
		constraint pk_client primary key(numClient)
	);
create table Admin
	(
		numAdmin int(1) AUTO_INCREMENT,
		idAdmin varchar (50) not null,
		mdpAdmin varchar (80) not null,
		constraint pk_admin primary key(numAdmin)
	);
create table Horaire
	(
		numHoraire smallint(2) AUTO_INCREMENT,
		heureDebut time not null,
		heureFin time not null,
		constraint pk_horaire primary key(numHoraire)
	);
create table Categorie
	(
		numCat int(2) AUTO_INCREMENT,
		nomCat varchar(25) not null,
		constraint pk_categorie primary key(numCat)
	);
create table Restaurant
	(
		numRest int(3) AUTO_INCREMENT,
		nomRest varchar(50) not null,
		rueRest varchar(100) not null,
		cpRest int(5) not null,
		villeRest varchar(45) not null,
		telRest char(10) not null,
		emailRest varchar(50) not null,
		etoileRest tinyint(1) not null,
		nbreTableRest tinyint(2) not null,
		catRest int(2) not null,
		formRepasRest varchar(7) not null,
		mdpRest varchar(60) not null,
		numImg int(3) not null,
		constraint pk_restaurant primary key(numRest),
		constraint un_nomRest unique(nomRest),
		constraint ck_formRepasRest check(formRepasRest in ('carte', 'menu', 'complet')),
		constraint fk_imgRest foreign key(numImg) references Image(numImg),
		constraint fk_catRest foreign key(catRest) references Categorie(numCat)
	);
create table Tables
	(
		idTable int(4) AUTO_INCREMENT,
		numTable int(2) not null,
		numRest int(3) not null,
		constraint pk_table primary key(idTable),
		constraint fk_restTable foreign key(numRest) references Restaurant(numRest)
	);
create table Plat
	(
		numPlat int(3) AUTO_INCREMENT,
		nomPlat varchar(30) not null,
		prixPlat float(4,2) not null,
		catPlat varchar(7) not null,
		numMenu int(3),
		numRest int(3) not null,
		constraint pk_plat primary key(numPlat),
		constraint fk_restplat foreign key(NumRest) references Restaurant(numRest),
		constraint fk_menuplat foreign key(NumMenu) references Menu(numMenu)
	);
create table Reservation
	(
		numResa int(5) AUTO_INCREMENT,
		dateResa datetime not null,
		nbrPersResa smallint(2) not null,
		commResa varchar(255) not null,
		idTable int(2) not null,
		numClient int(4) not null,
		constraint pk_resa primary key(numResa),
		constraint fk_resa_client foreign key(numClient) references Client(numClient),
		constraint fk_resa_table foreign key(idTable) references Tables(idTable)
	);