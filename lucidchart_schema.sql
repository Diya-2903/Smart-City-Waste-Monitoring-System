-- Lucidchart SQL Import Format
-- You can import this file directly into Lucidchart:
-- Go to File > Import Data > Entity Relationship > Import your SQL Database

CREATE TABLE adminlogin (
  Id int(11) NOT NULL,
  username varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  PRIMARY KEY (Id)
);

CREATE TABLE adminlogin_tbl (
  id int(11) NOT NULL,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  code mediumint(50) NOT NULL,
  status text NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE contact (
  fname varchar(20) NOT NULL,
  lname varchar(20) NOT NULL,
  contactEmail varchar(20) NOT NULL,
  contactPhone varchar(10) NOT NULL,
  comment varchar(2000) NOT NULL
);

CREATE TABLE usertable (
  id int(11) NOT NULL,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  code mediumint(50) NOT NULL,
  status text NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE garbageinfo (
  Id int(11) NOT NULL,
  name varchar(255) NOT NULL,
  mobile varchar(15) DEFAULT NULL,
  email varchar(255) NOT NULL,
  wastetype varchar(255) NOT NULL,
  location varchar(255) NOT NULL,
  locationdescription varchar(255) NOT NULL,
  file varchar(255) NOT NULL,
  date varchar(255) DEFAULT NULL,
  status varchar(50) DEFAULT NULL,
  PRIMARY KEY (Id)
);

-- Note: In the original database, foreign keys were not strictly enforced.
-- Below is the logical relationship for Lucidchart to draw the connection line.
-- usertable(email) --< garbageinfo(email)

ALTER TABLE garbageinfo ADD CONSTRAINT FK_User_Garbage FOREIGN KEY (email) REFERENCES usertable(email);
