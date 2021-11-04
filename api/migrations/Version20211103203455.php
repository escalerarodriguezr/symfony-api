<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103203455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Create 'movement_category' table an associations";
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE movement_category (id CHAR(36) NOT NULL, owner_id CHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, created_on DATETIME NOT NULL, updated_on DATETIME NOT NULL, INDEX IDX_movement_category_owner_id (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movement_category ADD CONSTRAINT FK_movement_category_owner FOREIGN KEY (owner_id) REFERENCES user (id) ON DELETE CASCADE');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE movement_category DROP FOREIGN KEY FK_movement_category_owner');
        $this->addSql('DROP TABLE movement_category');
    }
}
