<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250309154854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE experience_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE player_experience_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE experience (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE player_experience (id INT NOT NULL, player_id INT NOT NULL, experience_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A35453E99E6F5DF ON player_experience (player_id)');
        $this->addSql('CREATE INDEX IDX_5A35453E46E90E27 ON player_experience (experience_id)');
        $this->addSql('ALTER TABLE player_experience ADD CONSTRAINT FK_5A35453E99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_experience ADD CONSTRAINT FK_5A35453E46E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE experience_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE player_experience_id_seq CASCADE');
        $this->addSql('ALTER TABLE player_experience DROP CONSTRAINT FK_5A35453E99E6F5DF');
        $this->addSql('ALTER TABLE player_experience DROP CONSTRAINT FK_5A35453E46E90E27');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE player_experience');
    }
}
