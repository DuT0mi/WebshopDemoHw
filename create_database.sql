drop database if exists webshop;							-- adatbázis törlése, ha már létezik

create database webshop										-- adatbázis létrehozása, utf8 kódolást beállítása az ékezetek miatt
	default character set utf8								-- karakterkódolás beállítása
    default collate utf8_general_ci;						-- karakterkódolás beállítása
    
use webshop;												-- a további parancsok ebben az adatbázisban legyenek végrehajtva

-- Vevő tábla létrehozása
create table `vevo`
(
	`Vid` int(10) primary key auto_increment not null,		-- 10 bites egész szám
	`Vname` varchar(50) not null,							-- max 2*25 hosszú string (keresztnév + vezetéknév miatt)
    `Vemail` varchar(35) not null,							-- max 35 hosszú string
    `Vpassword` varchar(255) not null						-- max 255 hosszú string, a hashelés miatt
															-- ...
);

-- Termék tábla 
create table `coffee`
(
	`Cid` int(10) primary key auto_increment not null,		-- 10 bites egész szám
    `Cname` varchar(20) not null,							-- max 20 hosszú string
    `Ctype` varchar(20) not null,							-- max 20 hosszú string
		-- Termékre vonatkozó adatok:
				`Cmanufact` varchar(30) not null,			-- max 30 hosszú
				`Cimage` LONGBLOB  not null,				-- kép
				`Cplaceoforigin` varchar(30) not null,		-- származási hely
                `Cdb`	int default null					-- készleten lévő darabszám
);

-- Összekötő tábla "megvásárolt" ~ purchase 
create table `purchase`
(
 `Pid` int(10) primary key auto_increment not null,			-- max 10 bites egész szám
 `Pdb` int(10) default null,								-- hány darab terméket vásárolt
 `coffeeID` int(10) default null,							-- kávé ID
 `vevoID` int(10) default null,								-- vevő ID
 foreign key (coffeeID) references coffee(Cid),				-- külső kulcs beállítás
 foreign key (vevoID) references vevo(Vid)					-- külső kulcs beállítás
);
-- Admin user létrehozása
 insert into vevo(Vid,Vname,Vemail,Vpassword) values ('1','admin','admin@gmail.com','admin');
-- Teszteléshez pár termék
 insert into coffee (Cname,Ctype,Cmanufact,Cimage,Cplaceoforigin,Cdb) values ('Expert Gusto Forte','szemes','Lavazza','img/LavaZ/lavazza.jpg','Olaszország',6);
 insert into coffee (Cname,Ctype,Cmanufact,Cimage,Cplaceoforigin,Cdb) values ('Intermezzo','szemes','Segafredo','img/Sega/segafredo.jpg','Olaszország',6);
 insert into coffee (Cname,Ctype,Cmanufact,Cimage,Cplaceoforigin,Cdb) values ('Crema e Aroma Blue','szemes','Lavazza','img/LavaZ/lavazza2.jpg','Olaszország',6);

