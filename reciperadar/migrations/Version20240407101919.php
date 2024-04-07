<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407101919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantity DROP CONSTRAINT fk_9ff3163659d8a214');
        $this->addSql('DROP INDEX idx_9ff3163659d8a214');
        $this->addSql('ALTER TABLE quantity DROP recipe_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE quantity ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT fk_9ff3163659d8a214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9ff3163659d8a214 ON quantity (recipe_id)');
    }
}
