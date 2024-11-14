<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114173228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE Messages_id_message_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE Messages (id_message INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id_message))');
        $this->addSql('CREATE INDEX IDX_22747CC0F624B39D ON Messages (sender_id)');
        $this->addSql('CREATE INDEX IDX_22747CC0CD53EDB6 ON Messages (receiver_id)');
        $this->addSql('ALTER TABLE Messages ADD CONSTRAINT FK_22747CC0F624B39D FOREIGN KEY (sender_id) REFERENCES Users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Messages ADD CONSTRAINT FK_22747CC0CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES Users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE Messages_id_message_seq CASCADE');
        $this->addSql('ALTER TABLE Messages DROP CONSTRAINT FK_22747CC0F624B39D');
        $this->addSql('ALTER TABLE Messages DROP CONSTRAINT FK_22747CC0CD53EDB6');
        $this->addSql('DROP TABLE Messages');
    }
}
