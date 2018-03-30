create table billing_info
( 
	creditcard_num varchar(16),
	expiry_date varchar(4),
	cvv varchar(3),
	cardholder_name varchar(20),
	address varchar(50),
	phone_number varchar(10),
	user_id integer NOT NULL,
	PRIMARY KEY(creditcard_num)
);

create table market_item
(
	item_id integer PRIMARY KEY,
	user_id integer NOT NULL
);

create table users
( 
	user_id integer PRIMARY KEY,
	user_name varchar(10),
	user_password varchar(20),
	user_email varchar(35),
	user_balance decimal(14,2),
	user_TID integer,
	CONSTRAINT UC_user UNIQUE (user_name, user_email)
);

create table listing
(
	id integer PRIMARY KEY,
	market_item_id integer,
	user_id integer,
	listed_date date,
	listed_price decimal(14,2),
	quantity integer
);

create table game
(
	game_id integer PRIMARY KEY,
	game_title varchar(30),
	game_purchase_date date
);

create table item_belongsTo
(
	item_id integer PRIMARY KEY,
	item_quantity integer,
	item_description varchar(250),
	item_name varchar(30),
	game_id integer
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
	administrator_id integer NOT NULL
);



create table administrator
(
	administrator_id integer PRIMARY KEY
);


create table monitors
(
	administrator_id integer,
	user_id integer,
	market_item_id integer,
	PRIMARY KEY (administrator_id, user_id, market_item_id)
);


alter table billing_info
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

alter table market_item
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

alter table users
ADD FOREIGN KEY (user_TID) REFERENCES transaction_supervises(transaction_id)

alter table listing
ADD FOREIGN KEY (market_item_id) REFERENCES market_item(item_id)
ADD FOREIGN KEY (user_id) REFERENCES users (user_id);

alter table game
ADD FOREIGN KEY (game_id) REFERENCES market_item (item_id);

alter table item_belongsTo 
ADD FOREIGN KEY(item_id) REFERENCES market_item(item_id)
ADD FOREIGN KEY(game_id) REFERENCES game(game_id) ON DELETE CASCADE;

alter table transaction_supervises
ADD FOREIGN KEY (buyer_id) REFERENCES users (user_id)
ADD	FOREIGN KEY (seller_id) REFERENCES users (user_id)
ADD	FOREIGN KEY (market_item_id) REFERENCES market_item (item_id)
ADD	FOREIGN KEY (administrator_id) REFERENCES administrator (administrator_id);


alter table administrator
ADD FOREIGN KEY (administrator_id) REFERENCES users (user_id);

alter table monitors
ADD FOREIGN KEY (administrator_id) REFERENCES administrator (administrator_id)
ADD	FOREIGN KEY (user_id) REFERENCES users (user_id)
ADD	FOREIGN key (market_item_id) REFERENCES market_item (item_id);

commit;
