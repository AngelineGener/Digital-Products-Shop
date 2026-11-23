-- Sample data for digital_products_sign_in (no NULL values)
-- Run this in phpMyAdmin (SQL tab) with the database selected, AFTER importing schema.sql.
-- All three sample users have the password:  password123

-- Wipe old data first (children first, so foreign keys don't complain) and reset the IDs to 1
DELETE FROM products;
DELETE FROM categories;
DELETE FROM users;
ALTER TABLE products   AUTO_INCREMENT = 1;
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE users      AUTO_INCREMENT = 1;

-- USERS
INSERT INTO users (first_name, last_name, age, address, email, username, password, date_registered) VALUES
('Maria', 'Santos',    24, 'Dasmarinas, Cavite', 'maria@example.com', 'maria', '$2y$10$2S4u9t2tMRWx/IH.aAX1/.xEmxfm9t19B3TkNeMPi5MK2xEAk6tv6', '2026-11-01 09:00:00'),
('Juan',  'Dela Cruz', 22, 'Imus, Cavite',       'juan@example.com',  'juan',  '$2y$10$2S4u9t2tMRWx/IH.aAX1/.xEmxfm9t19B3TkNeMPi5MK2xEAk6tv6', '2026-11-01 09:30:00'),
('Ana',   'Reyes',     21, 'Bacoor, Cavite',     'ana@example.com',   'ana',   '$2y$10$2S4u9t2tMRWx/IH.aAX1/.xEmxfm9t19B3TkNeMPi5MK2xEAk6tv6', '2026-11-02 10:15:00');

-- CATEGORIES (parent)
INSERT INTO categories (category_name, description, added_by, last_updated_by, date_added, last_updated) VALUES
('Planners',   'Printable and digital planners for organizing daily life', 1, 2, '2026-11-03 10:00:00', '2026-11-08 09:10:00'),
('Templates',  'Ready-to-edit templates for Canva, Notion, and resumes',   1, 3, '2026-11-03 10:05:00', '2026-11-08 14:20:00'),
('E-books',    'Downloadable guides and how-to books',                     2, 1, '2026-11-04 11:00:00', '2026-11-09 10:30:00'),
('Presets',    'Photo editing presets for Lightroom and mobile',           2, 3, '2026-11-04 11:10:00', '2026-11-09 16:45:00'),
('Stickers',   'Digital stickers for GoodNotes and planners',              3, 1, '2026-11-05 13:30:00', '2026-11-10 08:50:00'),
('Worksheets', 'Printable worksheets for students and teachers',           3, 2, '2026-11-05 13:40:00', '2026-11-10 09:15:00');

-- PRODUCTS (children)
INSERT INTO products (category_id, title, description, price, file_type, image_path, added_by, last_updated_by, date_added, last_updated) VALUES
(1, 'Budget Planner',           'Monthly budget tracker with savings goals',   150.00, 'PDF', 'uploads/sample_01.svg', 1, 2, '2026-11-06 10:00:00', '2026-11-11 09:00:00'),
(1, 'Weekly Planner',           'Printable weekly schedule with to-do list',    99.00, 'PDF', 'uploads/sample_02.svg', 1, 3, '2026-11-06 10:10:00', '2026-11-11 15:00:00'),
(1, 'Meal Planner',             'Weekly meal plan with grocery checklist',     120.00, 'PDF', 'uploads/sample_03.svg', 2, 1, '2026-11-06 10:20:00', '2026-11-11 16:20:00'),
(2, 'Resume Template Pack',     'Five editable resume designs',                199.00, 'Canva', 'uploads/sample_04.svg', 1, 3, '2026-11-06 11:00:00', '2026-11-12 08:40:00'),
(2, 'Notion Dashboard',         'All-in-one productivity dashboard',           249.00, 'Notion', 'uploads/sample_05.svg', 1, 2, '2026-11-06 11:10:00', '2026-11-12 10:30:00'),
(2, 'Instagram Post Bundle',    '30 editable social media post templates',     179.00, 'Canva', 'uploads/sample_06.svg', 2, 1, '2026-11-06 11:20:00', '2026-11-12 11:10:00'),
(3, 'Freelancing 101',          'Beginner guide to starting freelance work',   299.00, 'PDF', 'uploads/sample_07.svg', 2, 3, '2026-11-07 09:00:00', '2026-11-12 12:00:00'),
(3, 'Study Hacks Guide',        'Study techniques for students',               129.00, 'PDF', 'uploads/sample_08.svg', 3, 1, '2026-11-07 09:10:00', '2026-11-12 13:45:00'),
(4, 'Warm Tones Preset Pack',   '10 presets for warm, golden-hour photos',     189.00, 'Lightroom', 'uploads/sample_09.svg', 2, 3, '2026-11-07 10:00:00', '2026-11-13 09:25:00'),
(4, 'Moody Film Presets',       '8 dark, cinematic film-look presets',         219.00, 'Lightroom', 'uploads/sample_10.svg', 1, 2, '2026-11-07 10:10:00', '2026-11-13 10:05:00'),
(5, 'Aesthetic Sticker Set',    '200 pastel digital stickers',                  89.00, 'GoodNotes', 'uploads/sample_11.svg', 3, 1, '2026-11-08 15:00:00', '2026-11-13 11:00:00'),
(5, 'Study Sticker Pack',       'Highlights, tabs, and labels for notes',       75.00, 'GoodNotes', 'uploads/sample_12.svg', 3, 2, '2026-11-08 15:10:00', '2026-11-13 11:20:00'),
(6, 'Math Practice Worksheets', '50 printable math drills for grades 4 to 6',  110.00, 'PDF', 'uploads/sample_13.svg', 3, 1, '2026-11-09 08:30:00', '2026-11-14 09:00:00'),
(6, 'Reading Log Printable',    'Weekly reading tracker for kids',              65.00, 'PDF', 'uploads/sample_14.svg', 2, 3, '2026-11-09 08:40:00', '2026-11-14 09:30:00');
