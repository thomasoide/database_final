# database_final

## Team Members

Leonard Tocco, Felipe Costa, Thomas Oide

## Purpose

Create an application where a financial planner would be able to see all of his/her clients as well as view a particular clients accounts. The user would first see a list of clients, with the ability to see all of the accounts for that given client. Types of accounts include retirement, investment and insurance. When adding or updating an account, the account's rating will be based on the account balance. There is currently no login page to enter the application, but having multiuser login function would make the application more useful. 

## Table Schema

### Clients

|Field   |Type   |Null   |Key   |Extra   |
|---|---|---|---|---|
|id|INT|NO|PRIMARY|AUTO_INCREMENT|
|firstName   |varChar(55)   |NO   |   |   |
|lastName   |varChar(55)   |NO   |   |   |
|email   |varChar(85)   |NO   |   |   |
|clientSince |date| NO| | 

### Accounts

|Field   |Type   |Null   |Key   |Extra   |
|---|---|---|---|---|
|id|INT|NO|PRIMARY|AUTO_INCREMENT|
|balance|INT|NO|||
|rating|varChar(10)|NO||
|clientID|INT|NO|FOREIGN||

## Entity Relationship Diagram

![alt text](https://github.com/thomasoide/database_final/blob/master/ERD.png "ERD")

## CRUD Explanations

#### Create

In the application, the user can add a client. Once the user views a particular client, the user can also add an account. 

#### Read

The opening view of the application is of all clients in the database. The user can also view all of the accounts under a specific client.

#### Update

The user can update both client and account information. 

#### Delete

The user can delete both client and account information. 

## Video Demonstration

https://www.youtube.com/watch?v=zFlh3L_3E0g&feature=youtu.be

## URL

http://www.latg24.epizy.com/index.php
