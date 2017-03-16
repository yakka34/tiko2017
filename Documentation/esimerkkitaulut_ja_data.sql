CREATE TABLE opiskelijat (nro INTEGER PRIMARY KEY, nimi VARCHAR(100), p_aine VARCHAR(100));
CREATE TABLE kurssit(id INTEGER PRIMARY KEY, nimi VARCHAR(100), opettaja VARCHAR(100));
CREATE TABLE suoritukset(
    k_id INTEGER NOT NULL REFERENCES kurssit(id), 
    op_nro INTEGER NOT NULL REFERENCES opiskelijat(nro), 
    arvosana INTEGER NOT NULL,
    PRIMARY KEY(k_id, op_nro)
);

INSERT INTO opiskelijat(nro, nimi, p_aine) VALUES(1, 'Maija', 'TKO');
INSERT INTO opiskelijat(nro, nimi, p_aine) VALUES(2, 'Ville', 'TKO');
INSERT INTO opiskelijat(nro, nimi, p_aine) VALUES(3, 'Kalle', 'VT');
INSERT INTO opiskelijat(nro, nimi, p_aine) VALUES(4, 'Liisa', 'VT');

INSERT INTO kurssit(id, nimi, opettaja) VALUES(1, 'tkp', 'KI');
INSERT INTO kurssit(id, nimi, opettaja) VALUES(2, 'oope', 'JL');
INSERT INTO kurssit(id, nimi, opettaja) VALUES(3, 'tiko', 'MJ');

INSERT INTO suoritukset(k_id, op_nro, arvosana) VALUES(1, 1, 5);
INSERT INTO suoritukset(k_id, op_nro, arvosana) VALUES(1, 2, 4);
INSERT INTO suoritukset(k_id, op_nro, arvosana) VALUES(1, 3, 2);
INSERT INTO suoritukset(k_id, op_nro, arvosana) VALUES(2, 1, 5);
INSERT INTO suoritukset(k_id, op_nro, arvosana) VALUES(2, 2, 3);
INSERT INTO suoritukset(k_id, op_nro, arvosana) VALUES(2, 4, 3);
INSERT INTO suoritukset(k_id, op_nro, arvosana) VALUES(3, 1, 5);
INSERT INTO suoritukset(k_id, op_nro, arvosana) VALUES(3, 2, 4);


--- => SELECT opettaja FROM kurssit;
---  opettaja
--- ----------
---  KI
---  JL
---  MJ
--- (3 rows)

--- => SELECT nimi FROM opiskelijat WHERE p_aine = 'TKO';
---  nimi
--- -------
---  Maija
---  Ville
--- (2 rows)

--- => SELECT suoritukset.arvosana FROM opiskelijat, suoritukset WHERE opiskelijat.nro = suoritukset.op_nro AND opiskelijat.nimi = 'Ville';
---  arvosana
--- ----------
---         4
---         3
---         4
--- (3 rows)

--- => INSERT INTO opiskelijat VALUES(1234, 'Matti', 'VT');
--- INSERT 0 1

--- => DELETE FROM opiskelijat WHERE nro = 1234;
--- DELETE 1
