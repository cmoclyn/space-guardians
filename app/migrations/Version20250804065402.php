<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250804065402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_20ecfee46a9bd80f');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_20ECFEE46A9BD80F ON queue_building (planet_building_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_20ECFEE46A9BD80F');
        $this->addSql('CREATE INDEX idx_20ecfee46a9bd80f ON queue_building (planet_building_id)');
    }
}
