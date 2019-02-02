CREATE DATABASE social_network CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use social_network;

create table post(
id int not null primary key auto_increment,
content text,
date datetime
)engine=InnoDB;

create table post
(
  id int not null primary key auto_increment,
  content text,
  post_id int not null

) engine = InnoDB;
