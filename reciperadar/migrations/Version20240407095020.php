<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407095020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quantity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_of_cuisine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE unit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quantity (id INT NOT NULL, unit_id INT NOT NULL, ingredient_id INT NOT NULL, recipe_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9FF31636F8BD700D ON quantity (unit_id)');
        $this->addSql('CREATE INDEX IDX_9FF31636933FE08C ON quantity (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_9FF3163659D8A214 ON quantity (recipe_id)');
        $this->addSql('CREATE TABLE type_of_cuisine (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE unit (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_followed_recipes (user_credentials_id INT NOT NULL, recipe_id INT NOT NULL, PRIMARY KEY(user_credentials_id, recipe_id))');
        $this->addSql('CREATE INDEX IDX_D63461002A6D6DBC ON user_followed_recipes (user_credentials_id)');
        $this->addSql('CREATE INDEX IDX_D634610059D8A214 ON user_followed_recipes (recipe_id)');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF31636F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF31636933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF3163659D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_followed_recipes ADD CONSTRAINT FK_D63461002A6D6DBC FOREIGN KEY (user_credentials_id) REFERENCES user_credentials (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_followed_recipes ADD CONSTRAINT FK_D634610059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe ADD type_of_cuisine VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user_credentials ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE quantity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_of_cuisine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE unit_id_seq CASCADE');
        $this->addSql('ALTER TABLE quantity DROP CONSTRAINT FK_9FF31636F8BD700D');
        $this->addSql('ALTER TABLE quantity DROP CONSTRAINT FK_9FF31636933FE08C');
        $this->addSql('ALTER TABLE quantity DROP CONSTRAINT FK_9FF3163659D8A214');
        $this->addSql('ALTER TABLE user_followed_recipes DROP CONSTRAINT FK_D63461002A6D6DBC');
        $this->addSql('ALTER TABLE user_followed_recipes DROP CONSTRAINT FK_D634610059D8A214');
        $this->addSql('DROP TABLE quantity');
        $this->addSql('DROP TABLE type_of_cuisine');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE user_followed_recipes');
        $this->addSql('ALTER TABLE recipe DROP type_of_cuisine');
        $this->addSql('ALTER TABLE user_credentials DROP created_at');
    }
}
