CREATE TABLE user (
	id int NOT NULL AUTO_INCREMENT,
	name TEXT NOT NULL,
	username TEXT UNIQUE NOT NULL,
	password TEXT NOT NULL,
	contact TEXT,
	email TEXT,
	isAdmin BOOLEAN NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE vehicule (
	id int NOT NULL AUTO_INCREMENT,
	name text NOT NULL,
	plate text UNIQUE NOT NULL,
	state boolean NOT NULL,
	idUser int NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY (idUser) REFERENCES user(id)
);

CREATE TABLE paymentMethod (
	id int NOT NULL AUTO_INCREMENT,
	name TEXT NOT NULL,
	description TEXT NOT NULL,
	idUser INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idUser) REFERENCES user(id)
);

CREATE TABLE park (
	id int NOT NULL AUTO_INCREMENT,
	name text NOT NULL,
	address TEXT NOT NULL,
	contact TEXT NOT NULL,
	email TEXT NOT NULL,
	totalSpaces int NOT NULL,
	localization text NOT NULL,
	nrFloors int NOT NULL,
	pricePerHour FLOAT NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE space (
	id int NOT NULL AUTO_INCREMENT,
	isReservativa boolean NOT NULL,
	idPark INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idPark) REFERENCES park(id)
);

CREATE TABLE liveSavedSpaces (
	id int NOT NULL AUTO_INCREMENT,
	saved_at DATE NOT NULL,
	idVehicule int UNIQUE NOT NULL,
	idSpace int UNIQUE NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idVehicule) REFERENCES vehicule(id),
	FOREIGN KEY (idSpace) REFERENCES space(id)
);

CREATE TABLE history (
	id int NOT NULL AUTO_INCREMENT,
	paid_at DATE NOT NULL,
	amount FLOAT NOT NULL,
	duration INT NOT NULL,
	idSpace int NOT NULL,
	idVehicule INT NOT NULL,
	idPaymentMethod INT NOT NULL,
	idUser INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (idSpace) REFERENCES space(id),
	FOREIGN KEY (idPaymentMethod) REFERENCES paymentMethod(id),
	FOREIGN KEY (idVehicule) REFERENCES vehicule(id)
	FOREIGN KEY (idUser) REFERENCES user(id)
);

INSERT INTO `user` (`id`, `name`, `username`, `password`, `contact`, `email`, `isAdmin`) VALUES
(1, 'João', 'joao', '123', '999888777', 'email@email.pt', 0),
(2, 'Test User', 'test', 'root', '123', 'email_Test@email.pt', 0);

INSERT INTO `vehicule` (`id`, `name`, `plate`, `state`, `idUser`) VALUES
(1, 'Toyota Supra', '11-11-11', 1, 1),
(2, 'Renault Clio', '22-11-33', 1, 1);

INSERT INTO `park` (`id`, `name`, `address`, `contact`, `email`,`totalSpaces`, `localization`, `nrFloors`, `pricePerHour`) VALUES
(1, 'Parque do Porto', "Rua do Porto", "222333222", "porto@email.pt", 60, 'Gondomar', 2, 0.8),
(2, 'Parque de Braga', "Rua de Braga", "333111333", "braga@email.pt", 40, 'Porto', 1, 0.3),
(3, 'Parque de Guimarães', "Rua de Guimarães", "123000123", "guimaraes@email.pt", 30, 'Guimarães', 2, 2.1),

INSERT INTO `space` (`id`, `idPark`, `isReservativa`) VALUES
(1, 1, 0),
(2, 1, 0),
(3, 1, 0),
(4, 1, 1),
(5, 1, 1),
(6, 2, 0),
(7, 2, 0),
(8, 2, 0),
(9, 2, 1),
(10, 2, 1),
(11, 3, 0),
(12, 3, 0),
(13, 3, 0),
(14, 3, 1),
(15, 3, 1);

INSERT INTO `paymentmethod` (`id`, `name`, `description`, `idUser`) VALUES 
(1, 'PayPal', 'email@gmail.com', 1),
(2, 'MBWay', '999888777', 1);