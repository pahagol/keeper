

create table Expense(
	id int not null auto_increment primary key,
	categoryId int not null,
	ownerId int not null,
	name varchar(255) not null,
	dateAdd date not null,
	price int not null,
	key categoryId (categoryId),
	key ownerId (ownerId)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

create table Category (
	id int not null auto_increment primary key,
	name varchar(255) not null
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

create table Owner (
	id int not null auto_increment primary key,
	name varchar(255) not null
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;