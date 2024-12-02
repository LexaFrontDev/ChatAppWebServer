<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202195805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_table ADD content TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE group_table ADD iv VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE group_table ADD sender VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE group_table DROP id_messages');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE group_table ADD id_messages INT NOT NULL');
        $this->addSql('ALTER TABLE group_table DROP content');
        $this->addSql('ALTER TABLE group_table DROP iv');
        $this->addSql('ALTER TABLE group_table DROP sender');
    }
}
