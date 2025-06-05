-- Products basic table
CREATE TABLE IF NOT EXISTS product (
  id INT(11) NOT NULL AUTO_INCREMENT,
  manufacturer_id SMALLINT(6) NOT NULL,
  name VARCHAR(255) NOT NULL,
  alias VARCHAR(255) NOT NULL,
  short_description TEXT NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(20,2) NOT NULL,
  image VARCHAR(255) NOT NULL,
  available SMALLINT(1) NOT NULL DEFAULT '1',
  meta_keywords VARCHAR(255) NOT NULL,
  meta_description VARCHAR(255) NOT NULL,
  meta_title VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY id (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- Additional product properties
CREATE TABLE IF NOT EXISTS product_properties (
  id INT(11) NOT NULL AUTO_INCREMENT,
  product_id INT(11) NOT NULL,
  property_name VARCHAR(255) NOT NULL,
  property_value VARCHAR(255) NOT NULL,
  property_price DECIMAL(20,2) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY id (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- Multiple images per product
CREATE TABLE IF NOT EXISTS product_images (
  id INT(11) NOT NULL AUTO_INCREMENT,
  product_id INT(11) NOT NULL,
  image VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY id (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- Guestbook entries
CREATE TABLE IF NOT EXISTS guestbook (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME NOT NULL,
  name VARCHAR(100) NOT NULL,
  city VARCHAR(100) DEFAULT NULL,
  rating TINYINT DEFAULT NULL,
  subscribe TINYINT(1) DEFAULT 0,
  product VARCHAR(100) DEFAULT NULL,
  comment TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Registered users
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample products
INSERT INTO product (manufacturer_id,name,alias,short_description,description,price,image,available,meta_keywords,meta_description,meta_title)
VALUES
  (1,'CRM‑PRO','crm-pro','Корпоративная CRM‑система для бизнеса','Полное описание CRM‑PRO',1999.99,'images/crm-thumb.jpg',1,'crm,erp','crm system','CRM‑PRO'),
  (2,'Mobile‑Suite','mobile-suite','Нативные мобильные приложения','Полное описание Mobile‑Suite',2999.50,'images/mobile-thumb.jpg',1,'mobile,apps','mobile apps','Mobile‑Suite');
