-- MANUAL migration — one-off cleanup of historically stored plaintext passwords.
--
-- As of this change the application never writes plaintext to these columns:
--   * affiliates.temp_pass    — was written on affiliate registration and read by
--                               the cron approval email; the approval email now
--                               generates a fresh one-time password at send time
--                               and stores only its bcrypt hash.
--   * customers.init_password — was written on POS customer creation alongside the
--                               bcrypt hash; nothing in the application ever read it.
--
-- This script NULLs the plaintext already sitting in the database. The columns are
-- intentionally left in the schema (dropping them is a separate operator decision).
--
-- Run whenever convenient:
--
--   mysql -u <user> -p <database> < db/migrations/2026-08-09_null_plaintext_passwords.sql
--
-- Idempotent: re-running is a harmless no-op (WHERE ... IS NOT NULL matches 0 rows).
--
-- Pre-flight verification — confirm the columns exist and live on these tables:
--
--   SHOW COLUMNS FROM affiliates LIKE 'temp_pass';
--   SHOW COLUMNS FROM customers  LIKE 'init_password';
--
-- Post-run verification — both counts must be 0:
--
--   SELECT COUNT(*) FROM affiliates WHERE temp_pass     IS NOT NULL;
--   SELECT COUNT(*) FROM customers  WHERE init_password IS NOT NULL;

UPDATE affiliates SET temp_pass = NULL WHERE temp_pass IS NOT NULL;

UPDATE customers SET init_password = NULL WHERE init_password IS NOT NULL;
