-- Optional, MANUAL migration — adds per-product AI SEO meta columns.
-- Referenced by docs/AI_SEO.md. The application tolerates these columns being
-- absent (every write is guarded by $this->db->field_exists('meta_title', 'products')),
-- so run this whenever convenient:
--
--   mysql -u <user> -p <database> < db/migrations/2026-07-07_add_product_seo_meta.sql
--
-- Idempotence: MySQL 5.7 has no ADD COLUMN IF NOT EXISTS; running this twice
-- fails harmlessly with "Duplicate column name". On MariaDB / MySQL 8.0.29+ you
-- may add IF NOT EXISTS to each clause.

ALTER TABLE products
    ADD COLUMN meta_title VARCHAR(255) NOT NULL DEFAULT '' AFTER seo_keywords,
    ADD COLUMN meta_description TEXT NULL AFTER meta_title,
    ADD COLUMN meta_keywords TEXT NULL AFTER meta_description;
