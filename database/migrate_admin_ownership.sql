-- Jalankan SEKALI pada database lama melalui phpMyAdmin atau MySQL.
-- Seluruh tempat lama akan menjadi milik akun admin dengan ID 1.

USE barcode_review;

ALTER TABLE places ADD COLUMN admin_id INT NULL AFTER id;
UPDATE places SET admin_id = 1 WHERE admin_id IS NULL;
ALTER TABLE places MODIFY admin_id INT NOT NULL;
ALTER TABLE places ADD INDEX idx_admin (admin_id);
ALTER TABLE places
    ADD CONSTRAINT fk_places_admin
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE;
