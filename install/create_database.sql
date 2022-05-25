CREATE DATABASE invsys;
CREATE USER 'invsys'@'localhost' IDENTIFIED BY 'password';
GRANT ALL on invsys.* to invsys@localhost;

USE DATABASE invsys;

CREATE TABLE items ( ID INT AUTO_INCREMENT PRIMARY KEY, ShortName VARCHAR(255), Description TEXT);
CREATE TABLE assignments ( ID INT AUTO_INCREMENT PRIMARY KEY, ItemID INT, AssignedTo INT, AssignedBy INT, Status INT);
CREATE TABLE entities ( ID INT AUTO_INCREMENT PRIMARY KEY, FullName VARCHAR(255), Description TEXT, Classification INT);
CREATE TABLE entity_to_user ( ID INT AUTO_INCREMENT PRIMARY KEY, EntityID INT, Username TEXT);
CREATE TABLE credentials ( ID INT AUTO_INCREMENT PRIMARY KEY, UserID INT, Credential VARCHAR(255), CredType INT);
CREATE TABLE entity_relationships ( ID INT AUTO_INCREMENT PRIMARY KEY,  EntityMaster INT,  EntitySub INT, Relationship INT);
