create table kayttaja(
  id SERIAL,
  nimi varchar(100) not null,
  opnro int,
  paine varchar(100),
  primary key(id)
);

create table rooli(
  id SERIAL,
  nimi varchar(100) not null,
  kuvaus varchar(500) not null,
  primary key(id)
);

create table roolit(
  kayttaja_id int,
  rooli_id int,
  primary key(kayttaja_id, rooli_id),
  foreign key (kayttaja_id) references kayttaja(id),
  foreign key (rooli_id) references rooli(id)
);

create table tehtava(
  id SERIAL,
  tekija_id int not null,
  kuvaus varchar(500) not null,
  kyselytyyppi varchar(8) not null,
  pvm date not null,
  esimvastaus varchar(1000) not null,
  primary key(id),
  foreign key(tekija_id) references kayttaja(id)
);

create table tehtavalista(
  id SERIAL,
  tekija_id int not null,
  pvm date not null,
  kuvaus varchar(500) not null,
  primary key(id),
  foreign key(tekija_id) references kayttaja(id)
);

create table tehtavat(
  lista_id int,
  tehtava_id int,
  primary key(lista_id, tehtava_id),
  foreign key(lista_id) references tehtavalista(id),
  foreign key(tehtava_id) references tehtava
);

create table sessio_tehtava(
  id SERIAL,
  sessio_id id not null,
  tehtava_id int not null,
  aloitus timestamp,
  lopetus timestamp,
  vastaus_oikein boolean not null,
  primary key(id),
  foreign key(tehtava_id) references tehtava(id),
  foreign key(sessio_id) references sessio(id)
);

create table tehtava_yritys(
  id SERIAL,
  sessio_tehtava_id int not null,
  aloitus timestamp,
  lopetus timestamp,
  vastaus varchar(1000),
  primary key(id),
  foreign key(sessio_tehtava_id) references sessio_tehtava(id)
);

create table sessio(
  id SERIAL,
  aloitus timestamp,
  lopetus timestamp,
  kayttaja_id int not null,
  tehtavalista_id int not null,
  primary key(id),
  foreign key(kayttaja_id) references kayttaja(id),
  foreign key(tehtavalista_id) references tehtavalista(id)
);
