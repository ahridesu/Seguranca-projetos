CREATE DATABASE IF NOT EXISTS `bookdb`;
USE `bookdb`; 

SET CHARSET 'utf8';


DROP TABLE IF EXISTS `Client`;
-- bookdb.Client definition
CREATE TABLE `Client` (
  `user_id`   int           NOT NULL  AUTO_INCREMENT,
  `username`  varchar(200)  NOT NULL,
  `password`  varchar(32)   NOT NULL,
  `email`     varchar(200)  NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Book`;
--  bookdb.Book definition
CREATE TABLE `Book` (
  `book_id`   int           NOT NULL  AUTO_INCREMENT,
  `title`     varchar(500)  NOT NULL,
  `author`    varchar(300)  NOT NULL,
  `publisher` varchar(200)  NULL,
  `price`     decimal(6,2)  NOT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


INSERT INTO bookdb.Client (username, password, email) VALUES 
('admin', md5('admin'), 'admin@protonmail.com');

INSERT INTO bookdb.Book (title, author, publisher, price) VALUES
('Aconcagua: The Invention of Mountaineering on America’s Highest Peak', 'Logan, Joy', 'University of Arizona Press', 10.99),
('Across a Great Divide', 'Laura L. Scheiber, Mark D Mitchell, and K. G Tregonning', 'University of Arizona Press', 15.80),
('Across the Plains: Sarah Royce’s Western Narrative', 'Dawes, Jennifer and Royce, Sarah', 'University of Arizona Press', 12.55),
('Activist Biology: The National Museum, Politics, and Nation Building in Brazil', 'Duarte, Regina Horta', 'University of Arizona Press', 20.00),
('After Collapse: The Regeneration of Complex Societies', 'Nichols, John J. and Schwartz, Glenn M.', 'University of Arizona Press', 9.10),
('Obscuritas', 'David Lagercrantz', 'Porto Editora', 8.00),
('Viagem a Portugal Edição Especial', 'José Saramago', 'Porto Editora', 12.99),
('Era uma vez em Hollywood', 'Quentin Tarantino', 'Porto Editora', 7.90),
('A Menina do Mar Edição Especial', 'Sophia de Mello Breyner Andresen', 'Porto Editora', 14.99),
('O Conto da Ilha Desconhecida', 'José Saramago', 'Porto Editora', 15.99),
('O ano de 1993', 'José Saramago', 'Porto Editora', 9.49),
('Último Caderno de Lanzarote', 'José Saramago', 'Porto Editora', 20.30),
('O Caderno', 'José Saramago', 'Porto Editora', 10.60),
('A Bagagem do Viajante', 'José Saramago', 'Porto Editora', 11.20),
('Obra Poética', 'Sophia de Mello Breyner Andresen', 'Porto Editora', 44.10),
('A Floresta', 'Sophia de Mello Breyner Andresen', 'Porto Editora', 12.15),
('O Rapaz de Bronze', 'Sophia de Mello Breyner Andresen', 'Porto Editora', 11.25),
('O Colar', 'Sophia de Mello Breyner Andresen', 'Porto Editora', 12.15),
('Contos Exemplares', 'Sophia de Mello Breyner Andresen', 'Porto Editora', 12.15),
('O Cavaleiro da Dinamarca', 'Sophia de Mello Breyner Andresen', 'Porto Editora', 12.15),
('Os Ciganos', 'Sophia de Mello Breyner Andresen', 'Porto Editora', 11.15),
('A Fada Oriana', 'Sophia de Mello Breyner Andresen', 'Porto Editora', 11.15);
