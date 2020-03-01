<?php

declare(strict_types=1);

namespace EvidApp\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200217235002 extends AbstractMigration
{

    public function getDescription(): string
    {
        return 'Add timestamps to user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD created_at TIMESTAMP NOT NULL, ADD updated_at TIMESTAMP DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP created_at, DROP updated_at');
    }
}