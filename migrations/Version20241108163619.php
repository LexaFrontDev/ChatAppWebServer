<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241108163619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Эта миграция была автоматически сгенерирована, пожалуйста, измените её по мере необходимости
        $this->addSql('DROP SEQUENCE messenger_messages_id_seq CASCADE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE mail_veryfication DROP CONSTRAINT fk_ee7f66ebf396750');
        $this->addSql('ALTER TABLE mail_veryfication ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mail_veryfication ADD CONSTRAINT FK_EE7F66EA76ED395 FOREIGN KEY (user_id) REFERENCES "Users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EE7F66EA76ED395 ON mail_veryfication (user_id)');
    }

    public function down(Schema $schema): void
    {
        // Эта миграция была автоматически сгенерирована, пожалуйста, измените её по мере необходимости
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE messenger_messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_75ea56e016ba31db ON messenger_messages (delivered_at)');
        $this->addSql('CREATE INDEX idx_75ea56e0e3bd61ce ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX idx_75ea56e0fb7336f0 ON messenger_messages (queue_name)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE mail_veryfication DROP CONSTRAINT FK_EE7F66EA76ED395');
        $this->addSql('DROP INDEX IDX_EE7F66EA76ED395');
        $this->addSql('ALTER TABLE mail_veryfication DROP user_id');
        $this->addSql('ALTER TABLE mail_veryfication ADD CONSTRAINT fk_ee7f66ebf396750 FOREIGN KEY (id) REFERENCES "Users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
