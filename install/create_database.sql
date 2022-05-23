CREATE DATABASE inventoryserver;
CREATE TABLE items (
	ID		INT AUTO_INCREMENT PRIMARY KEY,
	ShortName	VARCHAR(255),
	Description	TEXT
);
CREATE TABLE assignments (
	ID		INT AUTO_INCREMENT PRIMARY KEY,
	ItemID		INT,
	AssignedTo	INT,
	AssignedBy	INT
)
CREATE TABLE entities (
	ID		INT AUTO_INCREMENT PRIMARY KEY,
	FullName	VARCHAR(255),
	Description	TEXT,
	Classification	INT
)
CREATE TABLE entity_to_user (
	ID		INT AUTO_INCREMENT PRIMARY KEY,
	EntityID	INT,
	Username	VARCHAR(20)
)
CREATE TABLE credentials (
	ID		INT AUTO_INCREMENT PRIMARY KEY,
	UserID		INT,
	Password	VARCHAR(255)
)
CREATE TABLE entity_relationships (
	ID		INT AUTO_INCREMENT PRIMARY KEY,
	EntityMaster	INT,
	EntitySub	INT,
	Relationship	INT
)
