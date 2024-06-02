<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240602150406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE quantity_id_seq CASCADE');
        $this->addSql('ALTER TABLE quantity DROP CONSTRAINT fk_9ff31636f8bd700d');
        $this->addSql('ALTER TABLE quantity DROP CONSTRAINT fk_9ff31636933fe08c');
        $this->addSql('DROP TABLE quantity');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE quantity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quantity (id INT NOT NULL, unit_id INT NOT NULL, ingredient_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_9ff31636933fe08c ON quantity (ingredient_id)');
        $this->addSql('CREATE INDEX idx_9ff31636f8bd700d ON quantity (unit_id)');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT fk_9ff31636f8bd700d FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT fk_9ff31636933fe08c FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
