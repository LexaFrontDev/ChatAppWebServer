<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241203031338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE Subscribers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE Subscribers (id INT NOT NULL, idGroup INT NOT NULL, idUsers INT NOT NULL, groupName VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A951997B30E820BB ON Subscribers (groupName)');
        $this->addSql('ALTER TABLE group_table ADD description VARCHAR(400) NOT NULL');
        $this->addSql('ALTER TABLE group_table DROP subscribers');
        $this->addSql('ALTER TABLE group_table DROP rolesgroup');
        $this->addSql('ALTER TABLE group_table DROP content');
        $this->addSql('ALTER TABLE group_table DROP iv');
        $this->addSql('ALTER TABLE group_table DROP sender');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE Subscribers_id_seq CASCADE');
        $this->addSql('DROP TABLE Subscribers');
        $this->addSql('ALTER TABLE group_table ADD subscribers TEXT NOT NULL');
        $this->addSql('ALTER TABLE group_table ADD rolesgroup JSON NOT NULL');
        $this->addSql('ALTER TABLE group_table ADD content TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE group_table ADD iv VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE group_table ADD sender VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE group_table DROP description');
        $this->addSql('COMMENT ON COLUMN group_table.subscribers IS \'(DC2Type:array)\'');
    }
}
