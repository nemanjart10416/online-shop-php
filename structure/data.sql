DROP DATABASE IF EXISTS online_shop;
CREATE DATABASE online_shop;

USE online_shop;

CREATE TABLE users
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(255) UNIQUE                               NOT NULL,
    email      VARCHAR(255) UNIQUE                               NOT NULL,
    password   VARCHAR(255)                                      NOT NULL,
    first_name VARCHAR(255),
    last_name  VARCHAR(255),
    birthday   DATE,
    address    TEXT,
    phone      VARCHAR(20),
    role ENUM('registered', 'customer', 'seller', 'admin', 'super_admin') NOT NULL,
    status     ENUM ('not confirmed', 'confirmed', 'blocked')    NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password, first_name, last_name, birthday, address, phone, role, status, created_at, updated_at)
VALUES
-- Super Admin
('superadmin', 'superadmin@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'John', 'Doe', '1980-01-15', '123 Admin Street', '123-456-7890', 'super_admin', 'confirmed', NOW(), NOW()),

-- Admins
('admin1', 'admin1@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Alice', 'Smith', '1975-05-22', '456 Admin Street', '234-567-8901', 'admin', 'confirmed', NOW(), NOW()),
('admin2', 'admin2@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Bob', 'Johnson', '1982-03-10', '789 Admin Street', '345-678-9012', 'admin', 'not confirmed', NOW(), NOW()),
('admin3', 'admin3@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Eva', 'Brown', '1988-07-18', '101 Admin Street', '456-789-0123', 'admin', 'confirmed', NOW(), NOW()),
('admin4', 'admin4@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Daniel', 'Lee', '1979-11-05', '111 Admin Street', '567-890-1234', 'admin', 'blocked', NOW(), NOW()),
('admin5', 'admin5@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Linda', 'Wong', '1990-09-28', '222 Admin Street', '678-901-2345', 'admin', 'confirmed', NOW(), NOW()),

-- Sellers
('seller1', 'seller1@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Michael', 'Clark', '1985-06-10', '123 Seller Street', '789-012-3456', 'seller', 'confirmed', NOW(), NOW()),
('seller2', 'seller2@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Emma', 'Davis', '1983-04-12', '234 Seller Street', '890-123-4567', 'seller', 'confirmed', NOW(), NOW()),
('seller3', 'seller3@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Matthew', 'White', '1989-08-17', '345 Seller Street', '901-234-5678', 'seller', 'blocked', NOW(), NOW()),
('seller4', 'seller4@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Olivia', 'Johnson', '1982-11-25', '456 Seller Street', '012-345-6789', 'seller', 'confirmed', NOW(), NOW()),
('seller5', 'seller5@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'William', 'Brown', '1987-02-14', '567 Seller Street', '123-456-7890', 'seller', 'blocked', NOW(), NOW()),
('seller6', 'seller6@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Emily', 'Harris', '1995-03-20', '678 Seller Street', '234-567-8901', 'seller', 'confirmed', NOW(), NOW()),
('seller7', 'seller7@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Aiden', 'Cooper', '1981-10-15', '789 Seller Street', '345-678-9012', 'seller', 'confirmed', NOW(), NOW()),
('seller8', 'seller8@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Oliver', 'Fisher', '1973-09-27', '890 Seller Street', '456-789-0123', 'seller', 'blocked', NOW(), NOW()),
('seller9', 'seller9@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Harper', 'Parker', '1988-07-08', '901 Seller Street', '567-890-1234', 'seller', 'confirmed', NOW(), NOW()),
('seller10', 'seller10@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Zoe', 'Barnes', '1992-05-03', '912 Seller Street', '678-901-2345', 'seller', 'confirmed', NOW(), NOW()),

-- Customers
('customer1', 'customer1@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Sophia', 'Taylor', '1992-09-05', '123 Main Street', '234-567-8901', 'customer', 'confirmed', NOW(), NOW()),
('customer2', 'customer2@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Jackson', 'Evans', '1991-12-15', '456 Elm Street', '345-678-9012', 'customer', 'blocked', NOW(), NOW()),
('customer3', 'customer3@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Ava', 'Baker', '1994-06-22', '789 Oak Street', '456-789-0123', 'customer', 'confirmed', NOW(), NOW()),
('customer4', 'customer4@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Mia', 'Fisher', '1993-03-18', '101 Pine Street', '567-890-1234', 'customer', 'confirmed', NOW(), NOW()),
('customer5', 'customer5@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Liam', 'Young', '1995-08-30', '111 Cedar Street', '678-901-2345', 'customer', 'blocked', NOW(), NOW()),
('customer6', 'customer6@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Amelia', 'Parker', '1990-05-12', '222 Maple Street', '789-012-3456', 'customer', 'confirmed', NOW(), NOW()),
('customer7', 'customer7@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Lucas', 'Fisher', '1989-07-28', '333 Walnut Street', '890-123-4567', 'customer', 'confirmed', NOW(), NOW()),
('customer8', 'customer8@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Emma', 'Ward', '1987-02-09', '444 Birch Street', '901-234-5678', 'customer', 'blocked', NOW(), NOW()),
('customer9', 'customer9@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Benjamin', 'Foster', '1986-04-05', '555 Spruce Street', '012-345-6789', 'customer', 'confirmed', NOW(), NOW()),
('customer10', 'customer10@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Ella', 'Brooks', '1984-11-20', '666 Oak Street', '123-456-7890', 'customer', 'confirmed', NOW(), NOW()),

-- Registered Users
('user1', 'user1@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Noah', 'Lee', '1996-01-02', '777 Elm Street', '234-567-8901', 'registered', 'confirmed', NOW(), NOW()),
('user2', 'user2@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Ava', 'Diaz', '1998-04-12', '888 Oak Street', '345-678-9012', 'registered', 'not confirmed', NOW(), NOW()),
('user3', 'user3@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Oliver', 'Barnes', '1997-07-22', '999 Pine Street', '456-789-0123', 'registered', 'confirmed', NOW(), NOW()),
('user4', 'user4@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Amelia', 'Hill', '1999-05-18', '121 Cedar Street', '567-890-1234', 'registered', 'blocked', NOW(), NOW()),
('user5', 'user5@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Liam', 'Ward', '1993-11-30', '131 Maple Street', '678-901-2345', 'registered', 'confirmed', NOW(), NOW()),
('user6', 'user6@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Aria', 'Foster', '1992-08-15', '141 Walnut Street', '789-012-3456', 'registered', 'not confirmed', NOW(), NOW()),
('user7', 'user7@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Ethan', 'Cole', '1994-03-28', '151 Birch Street', '890-123-4567', 'registered', 'confirmed', NOW(), NOW()),
('user8', 'user8@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Avery', 'Fisher', '1991-06-09', '161 Spruce Street', '901-234-5678', 'registered', 'blocked', NOW(), NOW()),
('user9', 'user9@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Mia', 'Young', '1998-09-05', '171 Oak Street', '012-345-6789', 'registered', 'confirmed', NOW(), NOW()),
('user10', 'user10@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Lucas', 'Ward', '1997-12-18', '181 Elm Street', '123-456-7890', 'registered', 'confirmed', NOW(), NOW());

