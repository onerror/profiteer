create table order_items
(
	id       int auto_increment
		primary key,
	order_id int           null,
	ean      varchar(32)   not null,
	quantity int default 1 not null,
	price    int default 0 null comment 'price multiplied by 100. E.g. 1 dollar 20 cents will be 120',
	constraint order_items_id_uindex
		unique (id),
	constraint order_items_orders_id_fk
		foreign key (order_id) references orders (id)
);

INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (1, 1, '2345234', 1, 120);
INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (2, 1, '234534', 2, 130);
INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (3, 1, '342345', 3, 140);
INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (4, 2, '2345234', 10, 9000);
INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (5, 3, '2362356', 1, 200);
INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (6, 4, '2456253', 3, 300);
INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (7, 5, '2345', 3, 680);
INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (8, 6, '2345', 5, 300);
INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (9, 7, '2345', 1, 3000);
INSERT INTO test.order_items (id, order_id, ean, quantity, price)
VALUES (10, 7, '23456', 2, 4000);


