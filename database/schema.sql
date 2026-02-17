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

