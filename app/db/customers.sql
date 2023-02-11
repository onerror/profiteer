create table customers
(
	id         int auto_increment
		primary key,
	first_name varchar(32) null,
	last_name  varchar(32) null,
	email      varchar(32) not null,
	constraint customers_email_uindex
		unique (email)
);

INSERT INTO test.customers (id, first_name, last_name, email)
VALUES (1, 'sdfg', 'sdfg', 'sdfgfds@sdfgsd');
INSERT INTO test.customers (id, first_name, last_name, email)
VALUES (2, 'bsdfb', 'awerva', 'ete@dfgsdf');
INSERT INTO test.customers (id, first_name, last_name, email)
VALUES (3, 'bs', 'sdfb', 'vaf@sbdf');
INSERT INTO test.customers (id, first_name, last_name, email)
VALUES (4, 'asgdfb', 'btsd', 'gs4@gdf');
INSERT INTO test.customers (id, first_name, last_name, email)
VALUES (5, 'dfbe', 'rgfvbs4grv34@bsdf', 'vfd@vsbdf');

