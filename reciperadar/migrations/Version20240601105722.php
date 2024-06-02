<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601105722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_credentials DROP CONSTRAINT fk_531ee19ba76ed395');
        $this->addSql('DROP INDEX uniq_531ee19ba76ed395');
        $this->addSql('ALTER TABLE user_credentials DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_credentials ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_credentials ADD CONSTRAINT fk_531ee19ba76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_531ee19ba76ed395 ON user_credentials (user_id)');
    }
}
