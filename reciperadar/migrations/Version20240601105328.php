<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601105328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d6492a6d6dbc');
        $this->addSql('DROP INDEX uniq_8d93d6492a6d6dbc');
        $this->addSql('ALTER TABLE "user" DROP user_credentials_id');
        $this->addSql('ALTER TABLE user_credentials ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_credentials ADD CONSTRAINT FK_531EE19BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_531EE19BA76ED395 ON user_credentials (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_credentials DROP CONSTRAINT FK_531EE19BA76ED395');
        $this->addSql('DROP INDEX UNIQ_531EE19BA76ED395');
        $this->addSql('ALTER TABLE user_credentials DROP user_id');
        $this->addSql('ALTER TABLE "user" ADD user_credentials_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d6492a6d6dbc FOREIGN KEY (user_credentials_id) REFERENCES user_credentials (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d6492a6d6dbc ON "user" (user_credentials_id)');
    }
}
