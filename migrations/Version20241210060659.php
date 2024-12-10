<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210060659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE subscribers_id_seq CASCADE');
        $this->addSql('DROP TABLE subscribers');
        $this->addSql('ALTER TABLE group_table ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE group_table ADD id_users INT DEFAULT NULL');
        $this->addSql('ALTER TABLE group_table ADD name_users VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE group_table ADD roles JSON DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A605A421DAA5F7AC ON group_table (name_users)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE subscribers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE subscribers (id INT NOT NULL, idgroup INT NOT NULL, idusers INT NOT NULL, groupname VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_a951997b30e820bb ON subscribers (groupname)');
        $this->addSql('DROP INDEX UNIQ_A605A421DAA5F7AC');
        $this->addSql('ALTER TABLE group_table DROP type');
        $this->addSql('ALTER TABLE group_table DROP id_users');
        $this->addSql('ALTER TABLE group_table DROP name_users');
        $this->addSql('ALTER TABLE group_table DROP roles');
    }
}
