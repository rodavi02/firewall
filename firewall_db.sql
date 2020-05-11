USE firewall_db;
CREATE TABLE access (
user varchar(16) NOT NULL,
password varchar(40) NOT NULL,
CONSTRAINT user_pk PRIMARY KEY (user)
);
INSERT INTO access VALUES('juanfran',SHA('departamento'));
INSERT INTO access VALUES('joseangel',SHA('iliberis'));