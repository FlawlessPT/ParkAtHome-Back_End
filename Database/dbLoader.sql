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
	vehicule TEXT NOT NULL,
	plate TEXT NOT NULL,
	paymentMethod TEXT NOT NULL,
	idUser INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idSpace) REFERENCES space(id),
	FOREIGN KEY (idUser) REFERENCES user(id)
);

INSERT INTO `user` (`id`, `name`, `username`, `password`, `contact`, `email`, `isAdmin`) VALUES
(1, 'João Sousa', 'joao', '123', '333999222', 'joao@gmail.com', 0),
(2, 'Admin User', 'admin', 'root', '000000000', 'admin@parkathome.pt', 1),
(3, 'Super admin', 'root', 'admin', '111222333', 'super_admin@parkathome.pt', 1),
(4, 'Pedro Miguel', 'pedro', '123', '888111222', 'pedro-miguel@gmail.com', 0);


INSERT INTO `vehicule` (`id`, `name`, `plate`, `idUser`) VALUES
(1, 'Mitsubishi', '29-01-MT', 1),
(2, 'Fiat', '34-58-HF', 1),
(3, 'Opel', '12-05-LI', 1),
(4, 'Mitsubishi', 'AS-AS-00', 4),
(5, 'Fiat', 'AS-AS-01', 4),
(6, 'Opel', 'AS-AS-02', 4),
(7, 'Fiat', 'AS-AS-03', 4),
(8, 'Renault', 'AS-AS-04', 4),
(9, 'AsdAsd', 'AS-AS-05', 4),
(10, 'BlaBla', 'AS-AS-06', 4),
(11, 'BlaBla', 'AS-AS-07', 4),
(12, 'BlaBla', 'AS-AS-08', 4),
(13, 'BlaBla', 'AS-AS-09', 4);


INSERT INTO `park` (`id`, `name`, `address`, `contact`, `email`,`totalSpaces`, `localization`, `nrFloors`, `pricePerHour`, `idUser`) VALUES
(1, 'Parque do Porto', "Rua do Porto", "222333222", "porto@email.pt", 10, 'Gondomar', 1, 0.8, 2),
(2, 'Parque de Braga', "Rua de Braga", "333111333", "braga@email.pt", 10, 'Porto', 1, 0.3, 2),
(3, 'Parque de Guimarães', "Rua de Guimarães", "123000123", "guimaraes@email.pt", 15, 'Guimarães', 1, 2.1, 2),
(4, 'Test Park', 'Test Address', '222444000', 'testpark@email.pt', 5, 'Test Localization', 1, 0.7, 3);

INSERT INTO `space` (`id`, `idPark`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 3),
(22, 3),
(23, 3),
(24, 3),
(25, 3),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(30, 3),
(31, 3),
(32, 3),
(33, 3),
(34, 3),
(35, 3);

INSERT INTO `paymentmethod` (`id`, `name`, `description`, `idUser`) VALUES
(1, 'PayPal', 'joao@gmail.com', 1),
(2, 'MBWay', '913768390', 1),
(3, 'MBWay', '123456999', 4);

INSERT INTO `liveSavedSpaces` (`saved_at`, `idVehicule`, `idSpace`, `idUser`) VALUES
('2021-02-11 15:45:40', 4, 11, 4),
('2021-02-11 15:45:41', 5, 12, 4),
('2021-02-11 15:45:42', 6, 13, 4),
('2021-02-11 15:45:43', 7, 14, 4),
('2021-02-11 15:45:44', 8, 15, 4),
('2021-02-11 15:45:45', 9, 16, 4),
('2021-02-11 15:45:46', 10, 17, 4),
('2021-02-11 15:45:47', 11, 18, 4),
('2021-02-11 15:45:48', 12, 19, 4),
('2021-02-11 15:45:49', 13, 20, 4);