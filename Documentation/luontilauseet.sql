create table kayttaja(
  id SERIAL,
  nimi varchar(100),
  opnro int,
  paine varchar(100),
  primary key(id)
);

create table rooli(
  id SERIAL,
  nimi varchar(100),
  kuvaus varchar(500)
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
  tekija_id int,
  kuvaus varchar(500),
  kyselytyyppi varchar(8),
  pvm date,
  esimvastaus varchar(1000),
  primary key(id),
  foreign key(tekija_id) references kayttaja(id)
);

create table tehtavalista(
  id SERIAL,
  tekija_id int,
  pvm date,
  kuvaus varchar(500),
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
  tehtava_id int,
  aloitus datetime,
  lopetus datetime,
  vastaus_oikein boolean,
  primary key(id),
  foreign key(tehtava_id) references tehtava(id)
);

create table tehtava_yritys(
  id SERIAL,
  sessio_tehtava_id int,
  aloitus datetime,
  lopetus datetime,
  vastaus varchar(1000),
  primary key(id),
  foreign key(sessio_tehtava_id) references sessio_tehtava(id)
);

create table sessio(
  id SERIAL,
  aloitus datetime,
  lopetus datetime,
  kayttaja_id int,
  tehtavalista_id int,
  sessio_tehtava_id int,
  primary key(id),
  foreign key(kayttaja_id) references kayttaja(id),
  foreign key(tehtavalista_id) references tehtavalista(id),
  foreign key(sessio_tehtava_id) references sessio_tehtava(id)
);
