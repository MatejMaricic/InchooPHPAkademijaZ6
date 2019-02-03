CREATE DATABASE social_network CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use social_network;

create table post(
id int not null primary key auto_increment,
content text,
date datetime,
image varchar(250) DEFAULT NULL
)engine=InnoDB;

create table comments
(
  id int not null primary key auto_increment,
  content text,
  post_id int not null

) engine = InnoDB;