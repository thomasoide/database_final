CREATE TABLE client(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	firstName varchar(255),
	lastName varchar(255),
	email varchar(255)
);

INSERT INTO client
(firstName, lastName, email)
VALUES
('Leonard', 'Tocco', 'latg24@mail.missouri.edu');

INSERT INTO client
(firstName, lastName, email)
VALUES
('Thomas', 'Oide', 'toide@gmail.com');

INSERT INTO client
(firstName, lastName, email)
VALUES
('Felipe', 'Costa', 'fcosta@gmail.com');

INSERT INTO client
(firstName, lastName, email)
VALUES
('Eric', 'Andre', 'yourboyeric@hotmail.com');

INSERT INTO client
(firstName, lastName, email)
VALUES
('Kyle', 'Mooney', 'yourboytodd@insidesocalquickhit.com');

CREATE TABLE accounts(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	rating varchar(10),
	accountType enum('Retirement', 'Investment', 'Insurance'),
	balance decimal(13,2),
	clientID int(11)
);


//LT Accounts
INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Retirement', '22122','01');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Investment', '1000000','01');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('C', 'Investment', '6000','01');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('C', 'Investment', '15500','01');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('C', 'Insurance', '2500000','01');


//FC Accounts
INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Retirement', '3500000','02');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Retirement', '1553242','02');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Investment', '54567.62','02');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Investment', '84354.67','02');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Insurance', '5454678.98','02');


//TO Accounts
INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Retirement', '3534568.98','05');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Investment', '1553242','05');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Investment', '54567.62','05');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Investment', '84354.67','05');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Insurance', '5424158.98','05');


//EA Accounts
INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('F', 'Investment', '0','06');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('F', 'Investment', '10.91','06');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('F', 'Investment', '4.20','06');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('F', 'Investment', '0','06');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Insurance', '9424158.98','06');


//KM Accounts
INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('A', 'Investment', '10000000','07');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('B', 'Investment', '15000.00','07');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('C', 'Investment', '8564.76','07');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('D', 'Investment', '2543.98','07');

INSERT INTO accounts
(rating, accountType, balance, clientID)
VALUES
('F', 'Insurance', '438.98','07');