DROP DATABASE parkathome_mobile;

CREATE DATABASE parkathome_mobile;

use parkathome_mobile;

CREATE TABLE `user` (
	id int NOT NULL AUTO_INCREMENT,
	name TEXT NOT NULL,
	username TEXT UNIQUE NOT NULL,
	password TEXT NOT NULL,
	contact TEXT NULL DEFAULT '---',
	email TEXT NULL DEFAULT '---',
	isAdmin BOOLEAN NULL DEFAULT 0,
	PRIMARY KEY (id)
);

CREATE TABLE `vehicule` (
	id int NOT NULL AUTO_INCREMENT,
	name text NULL DEFAULT '---',
	plate text UNIQUE NOT NULL,
	state boolean NULL DEFAULT 0,
	idUser int NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY (idUser) REFERENCES user(id)
);

CREATE TABLE `paymentMethod` (
	id int NOT NULL AUTO_INCREMENT,
	name TEXT NULL DEFAULT '---',
	description TEXT NULL DEFAULT '---',
	idUser INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idUser) REFERENCES user(id)
);

CREATE TABLE `park` (
	id int NOT NULL AUTO_INCREMENT,
	name text NOT NULL,
	address TEXT NOT NULL,
	contact TEXT NOT NULL,
	email TEXT NOT NULL,
	totalSpaces int NOT NULL,
	localization text NOT NULL,
	nrFloors int NOT NULL,
	pricePerHour FLOAT NOT NULL,
	idUser INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idUser) REFERENCES user(id)
);

CREATE TABLE `space` (
	id int NOT NULL AUTO_INCREMENT,
	idPark INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idPark) REFERENCES park(id)
);

CREATE TABLE `liveSavedSpaces` (
	id int NOT NULL AUTO_INCREMENT,
	saved_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	idVehicule int UNIQUE NOT NULL,
	idSpace int UNIQUE NOT NULL,
	idUser int NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idVehicule) REFERENCES vehicule(id),
	FOREIGN KEY (idSpace) REFERENCES space(id),
	FOREIGN KEY (idUser) REFERENCES user(id)
);

CREATE TABLE `history` (
	id int NOT NULL AUTO_INCREMENT,
	paid_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	amount FLOAT NOT NULL,
	duration TEXT NOT NULL,
	idSpace int NOT NULL,
	idVehicule INT NOT NULL,
	idPaymentMethod INT NOT NULL,
	idUser INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idSpace) REFERENCES space(id),
	FOREIGN KEY (idPaymentMethod) REFERENCES paymentMethod(id),
	FOREIGN KEY (idVehicule) REFERENCES vehicule(id),
	FOREIGN KEY (idUser) REFERENCES user(id)
);

INSERT INTO `user` (`id`, `name`, `username`, `password`, `contact`, `email`, `isAdmin`) VALUES
(1, 'João Sousa', 'joao', '123', '333999222', 'joao@gmail.com', 0),
(2, 'Admin User', 'admin', 'root', '000000000', 'admin@parkathome.pt', 1),
(3, 'Super admin', 'root', 'admin', '111222333', 'super_admin@parkathome.pt', 1);

INSERT INTO `vehicule` (`id`, `name`, `plate`, `idUser`) VALUES
(1, 'Mitsubishi Space Star', '29-01-MT', 1),
(2, 'Fiat', '33-11-AA', 1),
(3, 'Opel', '44-33-CC', 1),
(4, 'Renault', '45-11-AS', 1),
(5, 'Peugeot', '45-88-DF', 1),
(6, '---', '---', 2),
(7, '---', '----', 3);

INSERT INTO `park` (`id`, `name`, `address`, `contact`, `email`,`totalSpaces`, `localization`, `nrFloors`, `pricePerHour`, `idUser`) VALUES
(1, 'Parque do Porto', "Rua do Porto", "222333222", "porto@email.pt", 15, 'Gondomar', 2, 0.8, 1),
(2, 'Parque de Braga', "Rua de Braga", "333111333", "braga@email.pt", 20, 'Porto', 1, 0.3, 1),
(3, 'Parque de Guimarães', "Rua de Guimarães", "123000123", "guimaraes@email.pt", 10, 'Guimarães', 2, 2.1, 3);

INSERT INTO `space` (`id`, `idPark`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 3);

INSERT INTO `paymentmethod` (`id`, `name`, `description`, `idUser`) VALUES
(1, 'PayPal', 'jotape919@gmail.com', 1),
(2, 'MBWay', '913768390', 1),
(3, '---', '---', 2),
(4, '---', '---', 3);
