CREATE DATABASE IF NOT EXISTS digital_products_sign_in;
USE digital_products_sign_in;

-- USER ENTITY
CREATE TABLE users (
    user_id         INT AUTO_INCREMENT PRIMARY KEY,
    first_name      VARCHAR(50)  NOT NULL,
    last_name       VARCHAR(50)  NOT NULL,
    age             INT          NULL,
    address         VARCHAR(255) NULL,
    email           VARCHAR(100) NOT NULL UNIQUE,
    username        VARCHAR(50)  NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PARENT ENTITY
CREATE TABLE categories (
    category_id     INT AUTO_INCREMENT PRIMARY KEY,
    category_name   VARCHAR(100) NOT NULL,
    description     TEXT,
    added_by        INT NOT NULL,
    last_updated_by INT NULL,
    date_added      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated    TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (added_by) REFERENCES users(user_id),
    FOREIGN KEY (last_updated_by) REFERENCES users(user_id)
);

-- CHILD ENTITY
CREATE TABLE products (
    product_id      INT AUTO_INCREMENT PRIMARY KEY,
    category_id     INT NOT NULL,
    title           VARCHAR(150) NOT NULL,
    description     TEXT,
    price           DECIMAL(10,2) NOT NULL,
    file_type       VARCHAR(50),
    image_path      VARCHAR(255) NOT NULL DEFAULT 'uploads/default.svg',
    is_deleted      TINYINT(1) NOT NULL DEFAULT 0,
    deleted_by      INT NULL,
    date_deleted    TIMESTAMP NULL DEFAULT NULL,
    added_by        INT NOT NULL,
    last_updated_by INT NULL,
    date_added      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated    TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE,
    FOREIGN KEY (added_by) REFERENCES users(user_id),
    FOREIGN KEY (last_updated_by) REFERENCES users(user_id),
    FOREIGN KEY (deleted_by) REFERENCES users(user_id)
);
