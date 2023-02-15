CREATE TABLE test.usd_data
(
	id                 INT auto_increment
		PRIMARY KEY,
	rate_buy           INT                                 NOT NULL,
	rate_sell          INT                                 NOT NULL,
	vector_rate_buy    INT                                 NOT NULL,
	timestamp_recorded TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	vector_rate_sell   INT NULL
);

