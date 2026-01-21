-- =====================================================
-- SCMCS Database Migrations - Consolidated SQL File
-- Generated from all Doctrine migrations
-- Date: 2025-01-21
-- =====================================================
-- 
-- This file contains all database migrations in order.
-- Import this file into phpMyAdmin to apply all migrations.
-- 
-- Migration Files:
-- 1. Version20260119070929.php
-- 2. Version20260121074749.php
-- 3. Version20260121083743.php
-- =====================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- =====================================================
-- Migration: Version20260119070929
-- =====================================================

-- Alter tbl_balance amount column
ALTER TABLE tbl_balance CHANGE amount amount DOUBLE PRECISION NOT NULL;

-- Add constraint to tbl_balance if it doesn't exist
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_F7A7178A9033DBEA' AND TABLE_NAME = 'tbl_balance');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_balance ADD CONSTRAINT FK_F7A7178A9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_balance if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_F7A7178A9033DBEA' AND TABLE_NAME = 'tbl_balance');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_F7A7178A9033DBEA ON tbl_balance (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_exc_comm
ALTER TABLE tbl_exc_comm CHANGE amount amount DOUBLE PRECISION NOT NULL;
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_C8F6F0C69033DBEA' AND TABLE_NAME = 'tbl_exc_comm');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_exc_comm ADD CONSTRAINT FK_C8F6F0C69033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_C8F6F0C69033DBEA' AND TABLE_NAME = 'tbl_exc_comm');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_C8F6F0C69033DBEA ON tbl_exc_comm (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_fixed_asset_loan
ALTER TABLE tbl_fixed_asset_loan CHANGE amount amount DOUBLE PRECISION NOT NULL;
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_703C65DE9033DBEA' AND TABLE_NAME = 'tbl_fixed_asset_loan');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_fixed_asset_loan ADD CONSTRAINT FK_703C65DE9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_703C65DE9033DBEA' AND TABLE_NAME = 'tbl_fixed_asset_loan');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_703C65DE9033DBEA ON tbl_fixed_asset_loan (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_form_fee
ALTER TABLE tbl_form_fee CHANGE amount amount DOUBLE PRECISION NOT NULL;
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_844511119033DBEA' AND TABLE_NAME = 'tbl_form_fee');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_form_fee ADD CONSTRAINT FK_844511119033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_844511119033DBEA' AND TABLE_NAME = 'tbl_form_fee');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_844511119033DBEA ON tbl_form_fee (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_form_fee_settings
ALTER TABLE tbl_form_fee_settings CHANGE amount amount DOUBLE PRECISION NOT NULL;

-- Alter tbl_gain
ALTER TABLE tbl_gain CHANGE requisition requisition DOUBLE PRECISION NOT NULL, CHANGE fixedAsset fixedAsset DOUBLE PRECISION NOT NULL, CHANGE watanda watanda DOUBLE PRECISION NOT NULL, CHANGE layya layya DOUBLE PRECISION NOT NULL, CHANGE formFee formFee DOUBLE PRECISION NOT NULL;

-- Alter tbl_layya
ALTER TABLE tbl_layya CHANGE amount amount DOUBLE PRECISION NOT NULL;
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_287677869033DBEA' AND TABLE_NAME = 'tbl_layya');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_layya ADD CONSTRAINT FK_287677869033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_287677869033DBEA' AND TABLE_NAME = 'tbl_layya');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_287677869033DBEA ON tbl_layya (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_monthly_deduction
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_C17CA2729033DBEA' AND TABLE_NAME = 'tbl_monthly_deduction');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_monthly_deduction ADD CONSTRAINT FK_C17CA2729033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_C17CA2729033DBEA' AND TABLE_NAME = 'tbl_monthly_deduction');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_C17CA2729033DBEA ON tbl_monthly_deduction (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_outstanding
ALTER TABLE tbl_outstanding CHANGE contribution contribution DOUBLE PRECISION NOT NULL;
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_965DBA3E9033DBEA' AND TABLE_NAME = 'tbl_outstanding');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_outstanding ADD CONSTRAINT FK_965DBA3E9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_965DBA3E9033DBEA' AND TABLE_NAME = 'tbl_outstanding');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_965DBA3E9033DBEA ON tbl_outstanding (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_permissions
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_AA8C2CF764B64DCC' AND TABLE_NAME = 'tbl_permissions');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_permissions ADD CONSTRAINT FK_AA8C2CF764B64DCC FOREIGN KEY (userId) REFERENCES `admin` (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_AA8C2CF7AEC98E42' AND TABLE_NAME = 'tbl_permissions');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_permissions ADD CONSTRAINT FK_AA8C2CF7AEC98E42 FOREIGN KEY (linkId) REFERENCES tbl_access_links (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_AA8C2CF764B64DCC' AND TABLE_NAME = 'tbl_permissions');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_AA8C2CF764B64DCC ON tbl_permissions (userId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_AA8C2CF7AEC98E42' AND TABLE_NAME = 'tbl_permissions');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_AA8C2CF7AEC98E42 ON tbl_permissions (linkId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_request
ALTER TABLE tbl_request CHANGE balance_bf balance_bf DOUBLE PRECISION NOT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE expensive expensive DOUBLE PRECISION NOT NULL;

-- Alter tbl_share
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_4162D7459033DBEA' AND TABLE_NAME = 'tbl_share');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_share ADD CONSTRAINT FK_4162D7459033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_4162D7459033DBEA' AND TABLE_NAME = 'tbl_share');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_4162D7459033DBEA ON tbl_share (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_soft_loan
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_45658A379033DBEA' AND TABLE_NAME = 'tbl_soft_loan');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_soft_loan ADD CONSTRAINT FK_45658A379033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_45658A379033DBEA' AND TABLE_NAME = 'tbl_soft_loan');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_45658A379033DBEA ON tbl_soft_loan (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_total_savings
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_FF8F9B649033DBEA' AND TABLE_NAME = 'tbl_total_savings');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_total_savings ADD CONSTRAINT FK_FF8F9B649033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_FF8F9B649033DBEA' AND TABLE_NAME = 'tbl_total_savings');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_FF8F9B649033DBEA ON tbl_total_savings (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_upgrade
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_EC357C6E9033DBEA' AND TABLE_NAME = 'tbl_upgrade');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_upgrade ADD CONSTRAINT FK_EC357C6E9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_EC357C6E9033DBEA' AND TABLE_NAME = 'tbl_upgrade');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_EC357C6E9033DBEA ON tbl_upgrade (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_upgrade_tmp
ALTER TABLE tbl_upgrade_tmp CHANGE amount amount DOUBLE PRECISION NOT NULL;
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_EC1B19539033DBEA' AND TABLE_NAME = 'tbl_upgrade_tmp');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_upgrade_tmp ADD CONSTRAINT FK_EC1B19539033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_EC1B19539033DBEA' AND TABLE_NAME = 'tbl_upgrade_tmp');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_EC1B19539033DBEA ON tbl_upgrade_tmp (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_watanda
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_DAFD2A7E9033DBEA' AND TABLE_NAME = 'tbl_watanda');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_watanda ADD CONSTRAINT FK_DAFD2A7E9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_DAFD2A7E9033DBEA' AND TABLE_NAME = 'tbl_watanda');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_DAFD2A7E9033DBEA ON tbl_watanda (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_withdrowal
ALTER TABLE tbl_withdrowal CHANGE amount amount DOUBLE PRECISION NOT NULL;
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_E4B106E79033DBEA' AND TABLE_NAME = 'tbl_withdrowal');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_withdrowal ADD CONSTRAINT FK_E4B106E79033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_E4B106E79033DBEA' AND TABLE_NAME = 'tbl_withdrowal');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_E4B106E79033DBEA ON tbl_withdrowal (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =====================================================
-- Migration: Version20260121074749
-- =====================================================

-- Create refresh_tokens table
CREATE TABLE IF NOT EXISTS refresh_tokens (
    id INT AUTO_INCREMENT NOT NULL,
    refresh_token VARCHAR(128) NOT NULL,
    username VARCHAR(255) NOT NULL,
    valid DATETIME NOT NULL,
    user_id INT NOT NULL,
    UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token),
    INDEX IDX_9BACE7E1A76ED395 (user_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

-- Create user table
CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT NOT NULL,
    email VARCHAR(180) NOT NULL,
    roles JSON NOT NULL,
    password VARCHAR(255) NOT NULL,
    enabled TINYINT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    member_id INT DEFAULT NULL,
    UNIQUE INDEX UNIQ_8D93D6497597D3FE (member_id),
    UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

-- Add foreign key constraints (these may already exist from previous migration, but we'll try to add them)
-- Note: Some constraints may already exist, so we'll use IF NOT EXISTS pattern where possible

-- Add constraint to refresh_tokens
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_9BACE7E1A76ED395' AND TABLE_NAME = 'refresh_tokens');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE refresh_tokens ADD CONSTRAINT FK_9BACE7E1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add constraint to user table
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_8D93D6497597D3FE' AND TABLE_NAME = 'user');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE user ADD CONSTRAINT FK_8D93D6497597D3FE FOREIGN KEY (member_id) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Re-add constraints that may have been added in previous migration (idempotent)
-- These will only be added if they don't already exist

-- tbl_exc_comm (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_C8F6F0C69033DBEA' AND TABLE_NAME = 'tbl_exc_comm');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_exc_comm ADD CONSTRAINT FK_C8F6F0C69033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tbl_fixed_asset_loan (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_703C65DE9033DBEA' AND TABLE_NAME = 'tbl_fixed_asset_loan');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_fixed_asset_loan ADD CONSTRAINT FK_703C65DE9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tbl_form_fee (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_844511119033DBEA' AND TABLE_NAME = 'tbl_form_fee');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_form_fee ADD CONSTRAINT FK_844511119033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_form_fee if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_844511119033DBEA' AND TABLE_NAME = 'tbl_form_fee');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_844511119033DBEA ON tbl_form_fee (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_form_fee_settings (may have been done in previous migration)
ALTER TABLE tbl_form_fee_settings CHANGE amount amount DOUBLE PRECISION NOT NULL;

-- Alter tbl_gain (may have been done in previous migration)
ALTER TABLE tbl_gain CHANGE requisition requisition DOUBLE PRECISION NOT NULL, CHANGE fixedAsset fixedAsset DOUBLE PRECISION NOT NULL, CHANGE watanda watanda DOUBLE PRECISION NOT NULL, CHANGE layya layya DOUBLE PRECISION NOT NULL, CHANGE formFee formFee DOUBLE PRECISION NOT NULL;

-- tbl_layya (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_287677869033DBEA' AND TABLE_NAME = 'tbl_layya');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_layya ADD CONSTRAINT FK_287677869033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE tbl_layya CHANGE amount amount DOUBLE PRECISION NOT NULL;

-- Create index on tbl_layya if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_287677869033DBEA' AND TABLE_NAME = 'tbl_layya');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_287677869033DBEA ON tbl_layya (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tbl_monthly_deduction (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_C17CA2729033DBEA' AND TABLE_NAME = 'tbl_monthly_deduction');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_monthly_deduction ADD CONSTRAINT FK_C17CA2729033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_monthly_deduction if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_C17CA2729033DBEA' AND TABLE_NAME = 'tbl_monthly_deduction');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_C17CA2729033DBEA ON tbl_monthly_deduction (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tbl_outstanding (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_965DBA3E9033DBEA' AND TABLE_NAME = 'tbl_outstanding');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_outstanding ADD CONSTRAINT FK_965DBA3E9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE tbl_outstanding CHANGE contribution contribution DOUBLE PRECISION NOT NULL;

-- Create index on tbl_outstanding if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_965DBA3E9033DBEA' AND TABLE_NAME = 'tbl_outstanding');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_965DBA3E9033DBEA ON tbl_outstanding (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tbl_permissions (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_AA8C2CF764B64DCC' AND TABLE_NAME = 'tbl_permissions');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_permissions ADD CONSTRAINT FK_AA8C2CF764B64DCC FOREIGN KEY (userId) REFERENCES `admin` (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_AA8C2CF7AEC98E42' AND TABLE_NAME = 'tbl_permissions');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_permissions ADD CONSTRAINT FK_AA8C2CF7AEC98E42 FOREIGN KEY (linkId) REFERENCES tbl_access_links (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create indexes on tbl_permissions if they don't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_AA8C2CF764B64DCC' AND TABLE_NAME = 'tbl_permissions');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_AA8C2CF764B64DCC ON tbl_permissions (userId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_AA8C2CF7AEC98E42' AND TABLE_NAME = 'tbl_permissions');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_AA8C2CF7AEC98E42 ON tbl_permissions (linkId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_request (may have been done in previous migration)
ALTER TABLE tbl_request CHANGE balance_bf balance_bf DOUBLE PRECISION NOT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE expensive expensive DOUBLE PRECISION NOT NULL;

-- tbl_share (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_4162D7459033DBEA' AND TABLE_NAME = 'tbl_share');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_share ADD CONSTRAINT FK_4162D7459033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_share if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_4162D7459033DBEA' AND TABLE_NAME = 'tbl_share');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_4162D7459033DBEA ON tbl_share (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tbl_soft_loan (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_45658A379033DBEA' AND TABLE_NAME = 'tbl_soft_loan');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_soft_loan ADD CONSTRAINT FK_45658A379033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_soft_loan if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_45658A379033DBEA' AND TABLE_NAME = 'tbl_soft_loan');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_45658A379033DBEA ON tbl_soft_loan (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tbl_total_savings (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_FF8F9B649033DBEA' AND TABLE_NAME = 'tbl_total_savings');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_total_savings ADD CONSTRAINT FK_FF8F9B649033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_total_savings if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_FF8F9B649033DBEA' AND TABLE_NAME = 'tbl_total_savings');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_FF8F9B649033DBEA ON tbl_total_savings (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tbl_upgrade (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_EC357C6E9033DBEA' AND TABLE_NAME = 'tbl_upgrade');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_upgrade ADD CONSTRAINT FK_EC357C6E9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_upgrade if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_EC357C6E9033DBEA' AND TABLE_NAME = 'tbl_upgrade');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_EC357C6E9033DBEA ON tbl_upgrade (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_upgrade_tmp
ALTER TABLE tbl_upgrade_tmp CHANGE amount amount DOUBLE PRECISION NOT NULL;

-- tbl_upgrade_tmp (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_EC1B19539033DBEA' AND TABLE_NAME = 'tbl_upgrade_tmp');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_upgrade_tmp ADD CONSTRAINT FK_EC1B19539033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_upgrade_tmp if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_EC1B19539033DBEA' AND TABLE_NAME = 'tbl_upgrade_tmp');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_EC1B19539033DBEA ON tbl_upgrade_tmp (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tbl_watanda (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_DAFD2A7E9033DBEA' AND TABLE_NAME = 'tbl_watanda');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_watanda ADD CONSTRAINT FK_DAFD2A7E9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_watanda if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_DAFD2A7E9033DBEA' AND TABLE_NAME = 'tbl_watanda');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_DAFD2A7E9033DBEA ON tbl_watanda (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter tbl_withdrowal
ALTER TABLE tbl_withdrowal CHANGE amount amount DOUBLE PRECISION NOT NULL;

-- tbl_withdrowal (may already exist)
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_E4B106E79033DBEA' AND TABLE_NAME = 'tbl_withdrowal');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_withdrowal ADD CONSTRAINT FK_E4B106E79033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create index on tbl_withdrowal if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_E4B106E79033DBEA' AND TABLE_NAME = 'tbl_withdrowal');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_E4B106E79033DBEA ON tbl_withdrowal (memberId)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =====================================================
-- Migration: Version20260121083743
-- =====================================================

-- Add password reset fields to user table (idempotent)
SET @column_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'user' AND COLUMN_NAME = 'password_reset_token');
SET @sql = IF(@column_exists = 0, 'ALTER TABLE user ADD password_reset_token VARCHAR(100) DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @column_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'user' AND COLUMN_NAME = 'password_reset_expires_at');
SET @sql = IF(@column_exists = 0, 'ALTER TABLE user ADD password_reset_expires_at DATETIME DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @column_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'user' AND COLUMN_NAME = 'must_change_password');
SET @sql = IF(@column_exists = 0, 'ALTER TABLE user ADD must_change_password TINYINT DEFAULT 0 NOT NULL', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create unique index on password_reset_token if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'UNIQ_8D93D6496B7BA4B6' AND TABLE_NAME = 'user');
SET @sql = IF(@index_exists = 0, 'CREATE UNIQUE INDEX UNIQ_8D93D6496B7BA4B6 ON user (password_reset_token)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =====================================================
-- Finalize
-- =====================================================

SET FOREIGN_KEY_CHECKS=1;
COMMIT;

-- =====================================================
-- Migration Complete
-- =====================================================

