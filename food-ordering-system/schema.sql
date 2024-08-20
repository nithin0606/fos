CREATE DATABASE order_food;

USE order_food;

CREATE TABLE users (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL  -- Assuming role can be either 'admin' or 'customer'
);


CREATE TABLE restaurants (
    id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(100) NOT NULL
);

CREATE TABLE dishes (
    id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(100) NOT NULL,
    restaurant_id INT,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    customer_address TEXT NOT NULL,
    dish_id INT,
    quantity INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    customer_username VARCHAR(50),
    FOREIGN KEY (dish_id) REFERENCES dishes(id),
    FOREIGN KEY (customer_username) REFERENCES users(username)
);

INSERT INTO users (username, password, role) VALUES
('admin', 'adminpassword', 'admin'),
('customer1', 'customer1password', 'customer'),
('customer2', 'customer2password', 'customer');

INSERT INTO restaurants (id,name, description, image) VALUES (1,'Restaurant 1', "Welcome to South Indian, where authentic South 
Indian flavors meet modern culinary delights. Step into a vibrant ambiance that celebrates the rich tapestry of South Indian cuisine, 
renowned for its aromatic spices, fresh ingredients, and bold flavors.Join us and immerse yourself in the flavors 
of South India. Taste tradition, taste innovation taste the essence of South Indian cuisine with us.", 'restaurant1.jpg');

INSERT INTO restaurants (id,name, description, image) VALUES (2,'Restaurant 2', "Welcome to Bissy Fastfood, where pizza meets burgers 
in a delicious fusion of flavors! Step into a cozy and inviting atmosphere that celebrates the best of both worlds,we specialize in crafting 
mouthwatering pizza burgers that combine the cheesy goodness of pizza with the hearty satisfaction of a burger.", 'restaurant2.jpg');

INSERT INTO restaurants (id,name, description, image) VALUES (3,'Restaurant 3', "Welcome to Chinese, where authentic Chinese 
flavors await you in a warm and inviting setting! Step into a culinary journey that celebrates the rich diversity of Chinese cuisine, 
we specialize in bringing you the best of Chinese culinary traditions with a modern twist. From savory dim sum to aromatic stir-fries and 
flavorful noodle dishes, our menu offers a tantalizing selection that caters to every palate.", 'restaurant3.jpg');

INSERT INTO restaurants (id,name, description, image) VALUES (4,'Restaurant 4', "Welcome to Katti rolls, your ultimate destination for 
delicious rolls packed with flavor and freshness! Step into a vibrant and inviting atmosphere where every bite is a celebration of taste 
and quality,we specialize in crafting mouthwatering rolls that are perfect for any time of the day. Whether you're craving a classic chicken 
tikka roll bursting with spicy flavors, a vegetarian paneer roll with a creamy twist, or a hearty lamb seekh kebab roll, our menu offers a 
variety of options to satisfy your taste buds.", 'restaurant4.jpg');

INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (5,'Meals', 100.00, 'dish1.jpg', 1);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (6,'Masala Dosa', 75.00, 'dish3.jpg', 1);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (7,'Idli', 35.00, 'dish4.jpg', 1);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (8,'Vada', 20.00, 'dish11.jpg', 1);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (9,'Rice bath', 35.00, 'dish12.jpg', 1);

INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (10,'waffles', 40.00, 'dish2.jpg', 2);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (11,'Burger', 65.00, 'dish5.jpg', 2);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (12,'Pizza', 105.00, 'dish6.jpg', 2);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (13,'Hotdog', 80.00, 'dish14.jpg', 2);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (14,'Taco', 55.00, 'dish15.jpg', 2);

INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (15,'Gobi munchurian', 100.00, 'dish7.jpg', 3);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (16,'Noodles', 150.00, 'dish21.jpg', 3);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (17,'Friderice', 100.00, 'dish16.jpg', 3);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (18,'Dimsum', 80.00, 'dish10.jpg', 3);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (19,'Soup', 80.00, 'dish22.jpg', 3);

INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (20,'Paneer Roll', 50.00, 'dish17.jpg', 4);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (21,'Veg Roll', 50.00, 'dish18.jpg', 4);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (22,'Cheese Roll', 50.00, 'dish19.jpg', 4);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (23,'Aloo Tikki Roll', 50.00, 'dish20.jpg', 4);
INSERT INTO dishes (id,name, price, image, restaurant_id) VALUES (24,'Kolkata Roll', 50.00, 'dish23.jpg', 4);

