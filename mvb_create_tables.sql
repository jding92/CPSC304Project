create table billing_info
( 
	creditcard_num varchar(16) PRIMARY KEY,
	expiry_date varchar(4),
	cvv varchar(3),
	cardholder_name varchar(20),
	address varchar(50),
	phone_number varchar(10),
	user_id integer NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users (user_id)
);

create table market_item
(
	item_id integer PRIMARY KEY,
	user_id integer NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users (user_id)
);

create table users
( 
	user_id integer PRIMARY KEY,
	user_name varchar(10),
	user_password varchar(20),
	user_email varchar(20),
	user_balance decimal(14,2),
	user_TID integer,
	user_creditcard varchar(16) UNIQUE,
	FOREIGN KEY (user_TID) REFERENCES transaction_supervises(transaction_id),
	FOREIGN KEY (user_creditcard) REFERENCES billing_info(creditcard_num),
	CONSTRAINT UC_user UNIQUE (user_name, user_email, user_creditcard)
);

create table listing
(
	market_item_id integer,
	user_id integer,
	listed_date date,
	listed_price decimal(14,2),
	quantity integer,
	PRIMARY KEY (market_item_id, user_id),
	FOREIGN KEY (market_item_id) REFERENCES market_item (item_id), 
	FOREIGN KEY (user_id) REFERENCES users (user_id)
);

create table game
(
	game_id integer PRIMARY KEY,
	game_title varchar(30),
	game_playtime timestamp,
	FOREIGN KEY (game_id) REFERENCES market_item (item_id)
);

create table item_belongsTo
(
	item_id integer PRIMARY KEY,
	item_quantity integer,
	item_description text,
	item_name varchar(30),
	game_id integer,
	FOREIGN KEY (item_id) REFERENCES market_item (item_id),
	FOREIGN KEY (game_id) REFERENCES game (game_id),
);

create table transaction_supervises
(
	transaction_id integer PRIMARY KEY,
	purchase_date date,
	purchase_price decimal(14,2),
	creditcard_num varchar(16),
	buyer_id integer NOT NULL,
	seller_id integer NOT NULL,
	market_item_id integer NOT NULL,
	administrator_id integer NOT NULL,
	FOREIGN KEY (buyer_id) REFERENCES users (user_id),
	FOREIGN KEY (seller_id) REFERENCES users (user_id),
	FOREIGN KEY (market_item_id) REFERENCES market_item (item_id),
	FOREIGN KEY (administrator_id) REFERENCES administrator (administrator_id)
);

create table administrator
(
	administrator_id integer PRIMARY KEY,
	FOREIGN KEY (administrator_id) REFERENCES users (user_id)

);

create table monitors
(
	administrator_id integer,
	user_id integer,
	market_item_id integer,
	PRIMARY KEY (administrator_id, user_id, market_item_id),
	FOREIGN KEY (administrator_id) REFERENCES administrator (administrator_id),
	FOREIGN KEY (user_id) REFERENCES users (user_id),
	FOREIGN key (market_item_id) REFERENCES market_item (item_id)
);


commit;
