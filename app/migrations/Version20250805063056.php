<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250805063056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet_buildings ADD queue_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE planet_buildings ADD CONSTRAINT FK_1B9CE95F477B5BAE FOREIGN KEY (queue_id) REFERENCES queue_building (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B9CE95F477B5BAE ON planet_buildings (queue_id)');
        $this->addSql('ALTER TABLE queue_building DROP CONSTRAINT fk_20ecfee46a9bd80f');
        $this->addSql('DROP INDEX uniq_20ecfee46a9bd80f');
        $this->addSql('ALTER TABLE queue_building DROP planet_building_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE planet_buildings DROP CONSTRAINT FK_1B9CE95F477B5BAE');
        $this->addSql('DROP INDEX UNIQ_1B9CE95F477B5BAE');
        $this->addSql('ALTER TABLE planet_buildings DROP queue_id');
        $this->addSql('ALTER TABLE queue_building ADD planet_building_id INT NOT NULL');
        $this->addSql('ALTER TABLE queue_building ADD CONSTRAINT fk_20ecfee46a9bd80f FOREIGN KEY (planet_building_id) REFERENCES planet_buildings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_20ecfee46a9bd80f ON queue_building (planet_building_id)');
    }
}
