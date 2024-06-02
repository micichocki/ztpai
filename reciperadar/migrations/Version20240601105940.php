<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601105940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD user_credentials_id INT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6492A6D6DBC FOREIGN KEY (user_credentials_id) REFERENCES user_credentials (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6492A6D6DBC ON "user" (user_credentials_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6492A6D6DBC');
        $this->addSql('DROP INDEX UNIQ_8D93D6492A6D6DBC');
        $this->addSql('ALTER TABLE "user" DROP user_credentials_id');
    }
}
