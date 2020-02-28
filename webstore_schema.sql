-- DROP TABLE IF EXISTS;
DROP TABLE IF EXISTS belongs;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS contains;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS product;

-- Add tables
CREATE TABLE product(
	pid INT AUTO_INCREMENT,
	pname VARCHAR(32),
	price DECIMAL(10,2),
	quantity INT,
	picture VARCHAR(32),
	PRIMARY KEY(pid)
);

CREATE TABLE users(
	uid INT AUTO_INCREMENT,
	fname VARCHAR(32),
	minit VARCHAR(1),
	lname VARCHAR(32),
	username VARCHAR(32),
        password VARCHAR(32),
	PRIMARY KEY(uid)	
);

CREATE TABLE cart(
	cid INT AUTO_INCREMENT,
	user_id INT,
	date_time DATETIME,
	FOREIGN KEY(user_id) REFERENCES users(uid),
	PRIMARY KEY(cid)
);

CREATE TABLE contains(
	cart_id INT,
	product_id INT,
	amount INT,
	FOREIGN KEY(cart_id) REFERENCES cart(cid),
	FOREIGN KEY(product_id) REFERENCES product(pid),
        PRIMARY KEY(cart_id, product_id)	
);

CREATE TABLE category(
	cat_name VARCHAR(32),
	PRIMARY KEY(cat_name)
);

CREATE TABLE belongs(
	ctg VARCHAR(32),
	product_id INT,
	FOREIGN KEY(ctg) REFERENCES category(cat_name),
	FOREIGN KEY(product_id) REFERENCES product(pid),
	PRIMARY KEY(ctg, product_id)
);

-- Populate products table
INSERT INTO product VALUES(1, 'Sweatpants', 20.42, 47, 'sweatpants.png');
INSERT INTO product VALUES(2, 'V-Neck', 15.99, 36, 'vneck.png');
INSERT INTO product VALUES(3, 'Eyeliner', 12.42, 23, 'eyeliner.png');
INSERT INTO product VALUES(4, 'Lipstick', 9.99, 26, 'lipstick.png');
INSERT INTO product VALUES(5, 'GTX 1080ti', 779.99, 12, '1080ti.png');
INSERT INTO product VALUES(6, 'R5 3600', 174.99, 20, 'r5_3600.png');
INSERT INTO product VALUES(7, 'Creampuffs', 14.58, 34, 'creampuffs.png');
INSERT INTO product VALUES(8, 'Taquitos', 16.99, 32, 'taquitos.png');
INSERT INTO product VALUES(9, 'Couch', 400.12, 3, 'couch.png');
INSERT INTO product VALUES(10, 'Desk', 69.69, 7, 'desk.png');
INSERT INTO product VALUES(11, 'Nonstick Pan', 19.99, 78, 'nonstick.png');
INSERT INTO product VALUES(12, 'Blender', 42.99, 60, 'blender.png');
INSERT INTO product VALUES(13, 'Power Drill', 139.87, 10, 'powerdrill.png');
INSERT INTO product VALUES(14, 'Power Saw', 219.00, 2, 'powersaw.png');
INSERT INTO product VALUES(15, 'Refridgerator', 1199.99, 5, 'refridgerator.png');

-- Initialize user accounts
INSERT INTO users VALUES(1, 'Chen', 'H', 'Liu', 'chliu', 'mypass123');
INSERT INTO users VALUES(2, 'Guest', null, null, 'Guest', 'guest123');

-- populate category table
INSERT INTO category VALUES('clothing');
INSERT INTO category VALUES('cosmetics');
INSERT INTO category VALUES('pchardware');
INSERT INTO category VALUES('food');
INSERT INTO category VALUES('furniture');
INSERT INTO category VALUES('kitchen');
INSERT INTO category VALUES('tools');
INSERT INTO category VALUES('home');
INSERT INTO category VALUES('fashion');

-- create belongs relation between product and category

-- clothing and cosmetics are both categories of fashion
INSERT INTO belongs VALUES('clothing', 1);
INSERT INTO belongs VALUES('fashion', 1);
INSERT INTO belongs VALUES('clothing', 2);
INSERT INTO belongs VALUES('fashion', 2);
INSERT INTO belongs VALUES('cosmetics', 3);
INSERT INTO belongs VALUES('fashion', 3);
INSERT INTO belongs VALUES('cosmetics', 4);
INSERT INTO belongs VALUES('fashion', 4);
INSERT INTO belongs VALUES('pchardware', 5);
INSERT INTO belongs VALUES('pchardware', 6);

-- food is related to some kitchen appliances and frozen food
INSERT INTO belongs VALUES('food', 7);
INSERT INTO belongs VALUES('food', 8);
INSERT INTO belongs VALUES('food', 11);
INSERT INTO belongs VALUES('food', 12);

-- furniture and kitchen
INSERT INTO belongs VALUES('furniture', 9);
INSERT INTO belongs VALUES('furniture', 10);
INSERT INTO belongs VALUES('kitchen', 11);
INSERT INTO belongs VALUES('kitchen', 12);

-- refridgerator and furniture are under home category as well
INSERT INTO belongs VALUES('home', 9);
INSERT INTO belongs VALUES('home', 10);
INSERT INTO belongs VALUES('home', 15);
INSERT INTO belongs VALUES('kitchen', 15);

-- tools
INSERT INTO belongs VALUES('tools', 13);
INSERT INTO belongs VALUES('tools', 14);





