<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407095346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe ADD type_of_cuisine_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe DROP type_of_cuisine');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13774F2DAB4 FOREIGN KEY (type_of_cuisine_id) REFERENCES type_of_cuisine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DA88B13774F2DAB4 ON recipe (type_of_cuisine_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B13774F2DAB4');
        $this->addSql('DROP INDEX IDX_DA88B13774F2DAB4');
        $this->addSql('ALTER TABLE recipe ADD type_of_cuisine VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE recipe DROP type_of_cuisine_id');
    }
}
