<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200217231555 extends AbstractMigration
{

    public function getDescription(): string
    {
        return 'Create User table';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('CREATE TABLE users (uuid UUID NOT NULL , credentials_email VARCHAR(255) NOT NULL,
                            credentials_password VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9299C9369 ON users (credentials_email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
