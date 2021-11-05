<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211105090617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Alter 'user' table add 'activation_code', 'confirmed_email' and 'active' columns";
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD activation_code VARCHAR(200) NOT NULL, ADD confirmed_email TINYINT(1) NOT NULL DEFAULT 0, ADD active TINYINT(1) NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {

        $this->addSql('ALTER TABLE user DROP activation_code, DROP confirmed_email, DROP active');
    }
}
