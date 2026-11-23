ALTER TABLE products
    ADD COLUMN image_path   VARCHAR(255) NOT NULL DEFAULT 'uploads/default.svg' AFTER file_type,
    ADD COLUMN is_deleted   TINYINT(1)   NOT NULL DEFAULT 0,
    ADD COLUMN deleted_by   INT NULL,
    ADD COLUMN date_deleted TIMESTAMP NULL DEFAULT NULL,
    ADD CONSTRAINT fk_products_deleted_by FOREIGN KEY (deleted_by) REFERENCES users(user_id);
