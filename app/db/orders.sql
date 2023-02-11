create table orders
(
	id            int auto_increment
		primary key,
	purchase_date timestamp not null,
	country_id    int       not null,
	device_id     int       null,
	customer_id   int       not null,
	constraint orders_customers_id_fk
		foreign key (customer_id) references customers (id)
);

INSERT INTO test.orders (id, purchase_date, country_id, device_id, customer_id)
VALUES (1, '2022-05-14 19:24:28', 3, 4, 1);
INSERT INTO test.orders (id, purchase_date, country_id, device_id, customer_id)
VALUES (2, '2022-04-14 19:24:40', 3, 4, 1);
INSERT INTO test.orders (id, purchase_date, country_id, device_id, customer_id)
VALUES (3, '2022-05-14 19:24:57', 3, 3, 2);
INSERT INTO test.orders (id, purchase_date, country_id, device_id, customer_id)
VALUES (4, '2022-05-16 19:24:03', 5, 3, 3);
INSERT INTO test.orders (id, purchase_date, country_id, device_id, customer_id)
VALUES (5, '2022-05-14 19:25:23', 4, 4, 4);
INSERT INTO test.orders (id, purchase_date, country_id, device_id, customer_id)
VALUES (6, '2022-05-10 19:25:35', 3, 3, 5);
INSERT INTO test.orders (id, purchase_date, country_id, device_id, customer_id)
VALUES (7, '2022-05-08 19:25:49', 3, 3, 5);

