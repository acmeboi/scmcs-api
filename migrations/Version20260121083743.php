<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260121083743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Add password reset fields to user table
        $this->addSql('ALTER TABLE user ADD password_reset_token VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD password_reset_expires_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD must_change_password TINYINT DEFAULT 0 NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496B7BA4B6 ON user (password_reset_token)');
    }

    public function down(Schema $schema): void
    {
        // Remove password reset fields from user table
        $this->addSql('DROP INDEX UNIQ_8D93D6496B7BA4B6 ON user');
        $this->addSql('ALTER TABLE user DROP password_reset_token');
        $this->addSql('ALTER TABLE user DROP password_reset_expires_at');
        $this->addSql('ALTER TABLE user DROP must_change_password');
    }
}
