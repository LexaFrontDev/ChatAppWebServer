<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210060021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE messages ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ALTER sender_id DROP NOT NULL');
        $this->addSql('ALTER TABLE messages ALTER receiver_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE messages DROP type');
        $this->addSql('ALTER TABLE messages DROP group_id');
        $this->addSql('ALTER TABLE messages ALTER sender_id SET NOT NULL');
        $this->addSql('ALTER TABLE messages ALTER receiver_id SET NOT NULL');
    }
}
