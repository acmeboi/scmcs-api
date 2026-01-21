<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260119070929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_balance CHANGE amount amount DOUBLE PRECISION NOT NULL');
        
        // Only add constraint if it doesn't exist
        $this->addSql("SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = 'FK_F7A7178A9033DBEA' AND TABLE_NAME = 'tbl_balance')");
        $this->addSql("SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE tbl_balance ADD CONSTRAINT FK_F7A7178A9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)', 'SELECT 1')");
        $this->addSql('PREPARE stmt FROM @sql');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');
        
        // Only create index if it doesn't exist
        $this->addSql("SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'IDX_F7A7178A9033DBEA' AND TABLE_NAME = 'tbl_balance')");
        $this->addSql("SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_F7A7178A9033DBEA ON tbl_balance (memberId)', 'SELECT 1')");
        $this->addSql('PREPARE stmt FROM @sql');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');
        $this->addSql('ALTER TABLE tbl_exc_comm CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_exc_comm ADD CONSTRAINT FK_C8F6F0C69033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_C8F6F0C69033DBEA ON tbl_exc_comm (memberId)');
        $this->addSql('ALTER TABLE tbl_fixed_asset_loan CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_fixed_asset_loan ADD CONSTRAINT FK_703C65DE9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_703C65DE9033DBEA ON tbl_fixed_asset_loan (memberId)');
        $this->addSql('ALTER TABLE tbl_form_fee CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_form_fee ADD CONSTRAINT FK_844511119033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_844511119033DBEA ON tbl_form_fee (memberId)');
        $this->addSql('ALTER TABLE tbl_form_fee_settings CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_gain CHANGE requisition requisition DOUBLE PRECISION NOT NULL, CHANGE fixedAsset fixedAsset DOUBLE PRECISION NOT NULL, CHANGE watanda watanda DOUBLE PRECISION NOT NULL, CHANGE layya layya DOUBLE PRECISION NOT NULL, CHANGE formFee formFee DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_layya CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_layya ADD CONSTRAINT FK_287677869033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_287677869033DBEA ON tbl_layya (memberId)');
        $this->addSql('ALTER TABLE tbl_monthly_deduction ADD CONSTRAINT FK_C17CA2729033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_C17CA2729033DBEA ON tbl_monthly_deduction (memberId)');
        $this->addSql('ALTER TABLE tbl_outstanding CHANGE contribution contribution DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_outstanding ADD CONSTRAINT FK_965DBA3E9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_965DBA3E9033DBEA ON tbl_outstanding (memberId)');
        $this->addSql('ALTER TABLE tbl_permissions ADD CONSTRAINT FK_AA8C2CF764B64DCC FOREIGN KEY (userId) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE tbl_permissions ADD CONSTRAINT FK_AA8C2CF7AEC98E42 FOREIGN KEY (linkId) REFERENCES tbl_access_links (id)');
        $this->addSql('CREATE INDEX IDX_AA8C2CF764B64DCC ON tbl_permissions (userId)');
        $this->addSql('CREATE INDEX IDX_AA8C2CF7AEC98E42 ON tbl_permissions (linkId)');
        $this->addSql('ALTER TABLE tbl_request CHANGE balance_bf balance_bf DOUBLE PRECISION NOT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE expensive expensive DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_share ADD CONSTRAINT FK_4162D7459033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_4162D7459033DBEA ON tbl_share (memberId)');
        $this->addSql('ALTER TABLE tbl_soft_loan ADD CONSTRAINT FK_45658A379033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_45658A379033DBEA ON tbl_soft_loan (memberId)');
        $this->addSql('ALTER TABLE tbl_total_savings ADD CONSTRAINT FK_FF8F9B649033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_FF8F9B649033DBEA ON tbl_total_savings (memberId)');
        $this->addSql('ALTER TABLE tbl_upgrade ADD CONSTRAINT FK_EC357C6E9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_EC357C6E9033DBEA ON tbl_upgrade (memberId)');
        $this->addSql('ALTER TABLE tbl_upgrade_tmp CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_upgrade_tmp ADD CONSTRAINT FK_EC1B19539033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_EC1B19539033DBEA ON tbl_upgrade_tmp (memberId)');
        $this->addSql('ALTER TABLE tbl_watanda ADD CONSTRAINT FK_DAFD2A7E9033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_DAFD2A7E9033DBEA ON tbl_watanda (memberId)');
        $this->addSql('ALTER TABLE tbl_withdrowal CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tbl_withdrowal ADD CONSTRAINT FK_E4B106E79033DBEA FOREIGN KEY (memberId) REFERENCES tbl_users (id)');
        $this->addSql('CREATE INDEX IDX_E4B106E79033DBEA ON tbl_withdrowal (memberId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_balance DROP FOREIGN KEY FK_F7A7178A9033DBEA');
        $this->addSql('DROP INDEX IDX_F7A7178A9033DBEA ON tbl_balance');
        $this->addSql('ALTER TABLE tbl_balance CHANGE amount amount FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_exc_comm DROP FOREIGN KEY FK_C8F6F0C69033DBEA');
        $this->addSql('DROP INDEX IDX_C8F6F0C69033DBEA ON tbl_exc_comm');
        $this->addSql('ALTER TABLE tbl_exc_comm CHANGE amount amount FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_fixed_asset_loan DROP FOREIGN KEY FK_703C65DE9033DBEA');
        $this->addSql('DROP INDEX IDX_703C65DE9033DBEA ON tbl_fixed_asset_loan');
        $this->addSql('ALTER TABLE tbl_fixed_asset_loan CHANGE amount amount FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_form_fee DROP FOREIGN KEY FK_844511119033DBEA');
        $this->addSql('DROP INDEX IDX_844511119033DBEA ON tbl_form_fee');
        $this->addSql('ALTER TABLE tbl_form_fee CHANGE amount amount FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_form_fee_settings CHANGE amount amount FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_gain CHANGE requisition requisition FLOAT NOT NULL, CHANGE fixedAsset fixedAsset FLOAT NOT NULL, CHANGE watanda watanda FLOAT NOT NULL, CHANGE layya layya FLOAT NOT NULL, CHANGE formFee formFee FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_layya DROP FOREIGN KEY FK_287677869033DBEA');
        $this->addSql('DROP INDEX IDX_287677869033DBEA ON tbl_layya');
        $this->addSql('ALTER TABLE tbl_layya CHANGE amount amount FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_monthly_deduction DROP FOREIGN KEY FK_C17CA2729033DBEA');
        $this->addSql('DROP INDEX IDX_C17CA2729033DBEA ON tbl_monthly_deduction');
        $this->addSql('ALTER TABLE tbl_outstanding DROP FOREIGN KEY FK_965DBA3E9033DBEA');
        $this->addSql('DROP INDEX IDX_965DBA3E9033DBEA ON tbl_outstanding');
        $this->addSql('ALTER TABLE tbl_outstanding CHANGE contribution contribution FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_permissions DROP FOREIGN KEY FK_AA8C2CF764B64DCC');
        $this->addSql('ALTER TABLE tbl_permissions DROP FOREIGN KEY FK_AA8C2CF7AEC98E42');
        $this->addSql('DROP INDEX IDX_AA8C2CF764B64DCC ON tbl_permissions');
        $this->addSql('DROP INDEX IDX_AA8C2CF7AEC98E42 ON tbl_permissions');
        $this->addSql('ALTER TABLE tbl_request CHANGE balance_bf balance_bf FLOAT NOT NULL, CHANGE amount amount FLOAT NOT NULL, CHANGE expensive expensive FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_share DROP FOREIGN KEY FK_4162D7459033DBEA');
        $this->addSql('DROP INDEX IDX_4162D7459033DBEA ON tbl_share');
        $this->addSql('ALTER TABLE tbl_soft_loan DROP FOREIGN KEY FK_45658A379033DBEA');
        $this->addSql('DROP INDEX IDX_45658A379033DBEA ON tbl_soft_loan');
        $this->addSql('ALTER TABLE tbl_total_savings DROP FOREIGN KEY FK_FF8F9B649033DBEA');
        $this->addSql('DROP INDEX IDX_FF8F9B649033DBEA ON tbl_total_savings');
        $this->addSql('ALTER TABLE tbl_upgrade DROP FOREIGN KEY FK_EC357C6E9033DBEA');
        $this->addSql('DROP INDEX IDX_EC357C6E9033DBEA ON tbl_upgrade');
        $this->addSql('ALTER TABLE tbl_upgrade_tmp DROP FOREIGN KEY FK_EC1B19539033DBEA');
        $this->addSql('DROP INDEX IDX_EC1B19539033DBEA ON tbl_upgrade_tmp');
        $this->addSql('ALTER TABLE tbl_upgrade_tmp CHANGE amount amount FLOAT NOT NULL');
        $this->addSql('ALTER TABLE tbl_watanda DROP FOREIGN KEY FK_DAFD2A7E9033DBEA');
        $this->addSql('DROP INDEX IDX_DAFD2A7E9033DBEA ON tbl_watanda');
        $this->addSql('ALTER TABLE tbl_withdrowal DROP FOREIGN KEY FK_E4B106E79033DBEA');
        $this->addSql('DROP INDEX IDX_E4B106E79033DBEA ON tbl_withdrowal');
        $this->addSql('ALTER TABLE tbl_withdrowal CHANGE amount amount FLOAT NOT NULL');
    }
}
