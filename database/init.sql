-- Script init rejouable
DROP DATABASE IF EXISTS vite_gourmand;

CREATE DATABASE IF NOT EXISTS vite_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vite_gourmand;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(190) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  address_line1 VARCHAR(255) NOT NULL,
  postal_code VARCHAR(20) NOT NULL,
  city VARCHAR(100) NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE themes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE regimes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE menus (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  theme_id INT NOT NULL,
  regime_id INT NOT NULL,
  min_people INT NOT NULL,
  base_price DECIMAL(10,2) NOT NULL,
  conditions_text TEXT,
  stock INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (theme_id) REFERENCES themes(id),
  FOREIGN KEY (regime_id) REFERENCES regimes(id)
);

CREATE TABLE menu_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  menu_id INT NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  caption VARCHAR(150),
  FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
);

CREATE TABLE dishes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  type ENUM('entree','plat','dessert') NOT NULL,
  description TEXT
);

CREATE TABLE menu_dishes (
  menu_id INT NOT NULL,
  dish_id INT NOT NULL,
  PRIMARY KEY (menu_id, dish_id),
  FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
  FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE
);

CREATE TABLE allergens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE dish_allergens (
  dish_id INT NOT NULL,
  allergen_id INT NOT NULL,
  PRIMARY KEY (dish_id, allergen_id),
  FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE,
  FOREIGN KEY (allergen_id) REFERENCES allergens(id) ON DELETE CASCADE
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  menu_id INT NOT NULL,
  status VARCHAR(50) NOT NULL,
  people_count INT NOT NULL,
  delivery_address VARCHAR(255) NOT NULL,
  delivery_city VARCHAR(100) NOT NULL,
  delivery_postal VARCHAR(20) NOT NULL,
  delivery_date DATE NOT NULL,
  delivery_time TIME NOT NULL,
  delivery_place VARCHAR(150) NOT NULL,
  distance_km DECIMAL(10,2) DEFAULT 0,
  delivery_fee DECIMAL(10,2) NOT NULL,
  menu_price DECIMAL(10,2) NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  cancellation_reason TEXT,
  cancellation_contact_mode VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (menu_id) REFERENCES menus(id)
);

CREATE TABLE order_status_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  status VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  rating INT NOT NULL,
  comment TEXT NOT NULL,
  is_validated TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL,
  title VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  status VARCHAR(50) DEFAULT 'nouveau',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE schedules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  day_of_week TINYINT NOT NULL,
  open_time TIME,
  close_time TIME,
  open_time2 TIME,
  close_time2 TIME,
  is_closed TINYINT(1) DEFAULT 0
);

CREATE TABLE password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL,
  token VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL
);


USE vite_gourmand;

INSERT INTO roles (name) VALUES ('admin'), ('employe'), ('utilisateur');

-- Admin par defaut (mot de passe: password)
INSERT INTO users (role_id, first_name, last_name, email, password_hash, phone, address_line1, postal_code, city)
VALUES (
  (SELECT id FROM roles WHERE name = 'admin'),
  'Jose', 'Admin', 'admin@vite-gourmand.fr',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9xO0xq2S4f32G.f31.rg9C',
  '0600000000', '25 rue des Gourmets', '33000', 'Bordeaux'
);

INSERT INTO themes (name) VALUES ('Noel'), ('Paques'), ('Classique'), ('Evenement');
INSERT INTO regimes (name) VALUES ('Classique'), ('Vegetarien'), ('Vegan');

INSERT INTO menus (title, description, theme_id, regime_id, min_people, base_price, conditions_text, stock)
VALUES
  ('Menu Classique', 'Un menu complet pour vos evenements familiaux.', (SELECT id FROM themes WHERE name='Classique'), (SELECT id FROM regimes WHERE name='Classique'), 10, 250.00, 'Commande 7 jours avant la prestation.', 5),
  ('Menu Vegan', 'Menu vegetal compose de produits locaux.', (SELECT id FROM themes WHERE name='Evenement'), (SELECT id FROM regimes WHERE name='Vegan'), 8, 220.00, 'Commande 5 jours avant la prestation.', 3),
  ('Menu Noel', 'Menu festif pour les celebrations de fin d''annee.', (SELECT id FROM themes WHERE name='Noel'), (SELECT id FROM regimes WHERE name='Classique'), 12, 300.00, 'Commande 14 jours avant la prestation.', 2);

INSERT INTO menu_images (menu_id, image_path, caption)
VALUES
  (1, '/images/filet%20de%20saumon.jpg', 'Filet de saumon'),
  (1, '/images/velout%C3%A9%20de%20saison.jpg', 'Veloute de saison'),
  (1, '/images/tarte%20fruits.jpg', 'Tarte aux fruits'),
  (2, '/images/quinoa%20salade.webp', 'Salade quinoa'),
  (2, '/images/curry%20de%20l%C3%A9gumes.jpeg', 'Curry de legumes'),
  (2, '/images/mousse%20chocolat.jpg', 'Mousse chocolat'),
  (3, '/images/tarte%20fruits.jpg', 'Tarte aux fruits'),
  (3, '/images/filet%20de%20saumon.jpg', 'Filet de saumon'),
  (3, '/images/velout%C3%A9%20de%20saison.jpg', 'Veloute de saison');

INSERT INTO dishes (name, type, description)
VALUES
  ('Veloute de saison', 'entree', 'Veloute de legumes du marche'),
  ('Filet de saumon', 'plat', 'Saumon sauce citron et herbes'),
  ('Tarte aux fruits', 'dessert', 'Fruits de saison'),
  ('Salade quinoa', 'entree', 'Quinoa, legumes croquants'),
  ('Curry de legumes', 'plat', 'Curry doux au lait de coco'),
  ('Mousse chocolat', 'dessert', 'Chocolat noir et amandes');

INSERT INTO menu_dishes (menu_id, dish_id)
VALUES
  (1, 1), (1, 2), (1, 3),
  (2, 4), (2, 5), (2, 6),
  (3, 1), (3, 2), (3, 3);

INSERT INTO allergens (name) VALUES ('Lait'), ('Gluten'), ('Arachide');

INSERT INTO dish_allergens (dish_id, allergen_id)
VALUES
  (2, 1),
  (3, 2),
  (6, 1);

INSERT INTO schedules (day_of_week, open_time, close_time, open_time2, close_time2, is_closed)
VALUES
  (1, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (2, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (3, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (4, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (5, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (6, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (7, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0);




