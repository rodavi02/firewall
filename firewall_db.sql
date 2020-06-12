USE firewall_db;

CREATE TABLE access (
user varchar(16) NOT NULL,
password varchar(40) NOT NULL,
CONSTRAINT user_pk PRIMARY KEY (user)
);

INSERT INTO access VALUES('juanfran',SHA('departamento'));
INSERT INTO access VALUES('joseangel',SHA('iliberis'));

CREATE TABLE rule (
id int(3) NOT NULL AUTO_INCREMENT,
action varchar(2) NOT NULL,
traffic varchar(7) NOT NULL,
int_in varchar(10),
int_out varchar(10),
source varchar(18),
destination varchar(18),
protocol varchar(10),
sport varchar(6),
dport varchar(6),
target varchar(6) NOT NULL,
CONSTRAINT rule_pk PRIMARY KEY (id)
);