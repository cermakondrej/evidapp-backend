<?php

declare(strict_types=1);

namespace EvidApp\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20200217231554 extends AbstractMigration
{

    public function getDescription() : string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE events (id SERIAL NOT NULL, uuid UUID NOT NULL, playhead INT NOT NULL, 
                            payload TEXT NOT NULL, metadata TEXT NOT NULL, recorded_on VARCHAR(32) NOT NULL, 
                            type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_5387574AD17F50A634B91FA9 ON events (uuid, playhead)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE events');
    }
}