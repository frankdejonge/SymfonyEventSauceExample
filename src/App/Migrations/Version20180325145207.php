<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180325145207 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE domain_messages (
    event_id UUID NOT NULL,
    event_type VARCHAR(255) NOT NULL,
    aggregate_root_id UUID NULL,
    time_of_recording TIMESTAMP(0) WITH TIME ZONE NOT NULL,
    payload JSON NOT NULL,
    PRIMARY KEY(event_id)
)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE domain_messages');
    }
}
