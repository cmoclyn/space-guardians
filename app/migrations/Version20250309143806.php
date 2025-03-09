<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250309143806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE building_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE building_resource_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE building_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fleet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE galaxy_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE guild_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE guild_rank_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE planet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE planet_buildings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE planet_resource_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE player_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE player_technology_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE queue_building_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE queue_ship_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE queue_technology_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE resource_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ship_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ship_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ship_model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ship_model_resource_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE solar_system_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE technology_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE technology_resource_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE technology_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE building (id INT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E16F61D4C54C8C93 ON building (type_id)');
        $this->addSql('CREATE TABLE building_resource (id INT NOT NULL, building_id INT NOT NULL, resource_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_671DF54D4D2A7E12 ON building_resource (building_id)');
        $this->addSql('CREATE INDEX IDX_671DF54D89329D25 ON building_resource (resource_id)');
        $this->addSql('CREATE TABLE building_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fleet (id INT NOT NULL, owner_id INT NOT NULL, position_x INT NOT NULL, position_y INT NOT NULL, name VARCHAR(255) NOT NULL, destination_x INT DEFAULT NULL, destination_y INT DEFAULT NULL, departure_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, arrival_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_moving BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A05E1E477E3C61F9 ON fleet (owner_id)');
        $this->addSql('COMMENT ON COLUMN fleet.departure_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN fleet.arrival_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE galaxy (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE guild (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE guild_rank (id INT NOT NULL, guild_id INT NOT NULL, name VARCHAR(255) NOT NULL, rank INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_692C4E4B5F2131EF ON guild_rank (guild_id)');
        $this->addSql('CREATE TABLE planet (id INT NOT NULL, owner_id INT DEFAULT NULL, solar_system_id INT NOT NULL, name VARCHAR(255) NOT NULL, position_x INT NOT NULL, position_y INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_68136AA57E3C61F9 ON planet (owner_id)');
        $this->addSql('CREATE INDEX IDX_68136AA5E5C8C6D3 ON planet (solar_system_id)');
        $this->addSql('CREATE TABLE planet_buildings (id INT NOT NULL, planet_id INT NOT NULL, building_id INT NOT NULL, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B9CE95FA25E9820 ON planet_buildings (planet_id)');
        $this->addSql('CREATE INDEX IDX_1B9CE95F4D2A7E12 ON planet_buildings (building_id)');
        $this->addSql('CREATE TABLE planet_resource (id INT NOT NULL, planet_id INT NOT NULL, resource_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CFD8C1C2A25E9820 ON planet_resource (planet_id)');
        $this->addSql('CREATE INDEX IDX_CFD8C1C289329D25 ON planet_resource (resource_id)');
        $this->addSql('CREATE TABLE player (id INT NOT NULL, guild_rank_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98197A6546ACA910 ON player (guild_rank_id)');
        $this->addSql('CREATE TABLE player_technology (id INT NOT NULL, player_id INT NOT NULL, technology_id INT NOT NULL, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ABC6D67099E6F5DF ON player_technology (player_id)');
        $this->addSql('CREATE INDEX IDX_ABC6D6704235D463 ON player_technology (technology_id)');
        $this->addSql('CREATE TABLE queue_building (id INT NOT NULL, planet_building_id INT NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_20ECFEE46A9BD80F ON queue_building (planet_building_id)');
        $this->addSql('COMMENT ON COLUMN queue_building.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN queue_building.finished_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE queue_ship (id INT NOT NULL, ship_model_id INT NOT NULL, planet_id INT NOT NULL, quantity INT NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F60A3D24A788732 ON queue_ship (ship_model_id)');
        $this->addSql('CREATE INDEX IDX_5F60A3D2A25E9820 ON queue_ship (planet_id)');
        $this->addSql('COMMENT ON COLUMN queue_ship.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN queue_ship.finished_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE queue_technology (id INT NOT NULL, player_technology_id INT NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B952BE897D5A8D7 ON queue_technology (player_technology_id)');
        $this->addSql('COMMENT ON COLUMN queue_technology.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN queue_technology.finished_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE resource (id INT NOT NULL, name VARCHAR(255) NOT NULL, coef DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ship (id INT NOT NULL, model_id INT NOT NULL, owner_id INT NOT NULL, fleet_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FA30EB247975B7E7 ON ship (model_id)');
        $this->addSql('CREATE INDEX IDX_FA30EB247E3C61F9 ON ship (owner_id)');
        $this->addSql('CREATE INDEX IDX_FA30EB244B061DF9 ON ship (fleet_id)');
        $this->addSql('CREATE TABLE ship_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ship_model (id INT NOT NULL, category_id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, speed DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D6EC210C12469DE2 ON ship_model (category_id)');
        $this->addSql('CREATE INDEX IDX_D6EC210C7E3C61F9 ON ship_model (owner_id)');
        $this->addSql('CREATE TABLE ship_model_technology (ship_model_id INT NOT NULL, technology_id INT NOT NULL, PRIMARY KEY(ship_model_id, technology_id))');
        $this->addSql('CREATE INDEX IDX_21DD55784A788732 ON ship_model_technology (ship_model_id)');
        $this->addSql('CREATE INDEX IDX_21DD55784235D463 ON ship_model_technology (technology_id)');
        $this->addSql('CREATE TABLE ship_model_resource (id INT NOT NULL, ship_model_id INT NOT NULL, resource_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_41DE13FB4A788732 ON ship_model_resource (ship_model_id)');
        $this->addSql('CREATE INDEX IDX_41DE13FB89329D25 ON ship_model_resource (resource_id)');
        $this->addSql('CREATE TABLE solar_system (id INT NOT NULL, galaxy_id INT NOT NULL, name VARCHAR(255) NOT NULL, position_x INT NOT NULL, position_y INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_28893917B61FAB2 ON solar_system (galaxy_id)');
        $this->addSql('CREATE TABLE technology (id INT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F463524DC54C8C93 ON technology (type_id)');
        $this->addSql('CREATE TABLE technology_resource (id INT NOT NULL, technology_id INT NOT NULL, resource_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9ED2ACEA4235D463 ON technology_resource (technology_id)');
        $this->addSql('CREATE INDEX IDX_9ED2ACEA89329D25 ON technology_resource (resource_id)');
        $this->addSql('CREATE TABLE technology_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D4C54C8C93 FOREIGN KEY (type_id) REFERENCES building_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE building_resource ADD CONSTRAINT FK_671DF54D4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE building_resource ADD CONSTRAINT FK_671DF54D89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fleet ADD CONSTRAINT FK_A05E1E477E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guild_rank ADD CONSTRAINT FK_692C4E4B5F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA57E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA5E5C8C6D3 FOREIGN KEY (solar_system_id) REFERENCES solar_system (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planet_buildings ADD CONSTRAINT FK_1B9CE95FA25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planet_buildings ADD CONSTRAINT FK_1B9CE95F4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planet_resource ADD CONSTRAINT FK_CFD8C1C2A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planet_resource ADD CONSTRAINT FK_CFD8C1C289329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6546ACA910 FOREIGN KEY (guild_rank_id) REFERENCES guild_rank (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_technology ADD CONSTRAINT FK_ABC6D67099E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_technology ADD CONSTRAINT FK_ABC6D6704235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE queue_building ADD CONSTRAINT FK_20ECFEE46A9BD80F FOREIGN KEY (planet_building_id) REFERENCES planet_buildings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE queue_ship ADD CONSTRAINT FK_5F60A3D24A788732 FOREIGN KEY (ship_model_id) REFERENCES ship_model (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE queue_ship ADD CONSTRAINT FK_5F60A3D2A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE queue_technology ADD CONSTRAINT FK_4B952BE897D5A8D7 FOREIGN KEY (player_technology_id) REFERENCES player_technology (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship ADD CONSTRAINT FK_FA30EB247975B7E7 FOREIGN KEY (model_id) REFERENCES ship_model (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship ADD CONSTRAINT FK_FA30EB247E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship ADD CONSTRAINT FK_FA30EB244B061DF9 FOREIGN KEY (fleet_id) REFERENCES fleet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_model ADD CONSTRAINT FK_D6EC210C12469DE2 FOREIGN KEY (category_id) REFERENCES ship_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_model ADD CONSTRAINT FK_D6EC210C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_model_technology ADD CONSTRAINT FK_21DD55784A788732 FOREIGN KEY (ship_model_id) REFERENCES ship_model (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_model_technology ADD CONSTRAINT FK_21DD55784235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_model_resource ADD CONSTRAINT FK_41DE13FB4A788732 FOREIGN KEY (ship_model_id) REFERENCES ship_model (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_model_resource ADD CONSTRAINT FK_41DE13FB89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE solar_system ADD CONSTRAINT FK_28893917B61FAB2 FOREIGN KEY (galaxy_id) REFERENCES galaxy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE technology ADD CONSTRAINT FK_F463524DC54C8C93 FOREIGN KEY (type_id) REFERENCES technology_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE technology_resource ADD CONSTRAINT FK_9ED2ACEA4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE technology_resource ADD CONSTRAINT FK_9ED2ACEA89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE building_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE building_resource_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE building_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fleet_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE galaxy_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE guild_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE guild_rank_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE planet_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE planet_buildings_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE planet_resource_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE player_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE player_technology_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE queue_building_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE queue_ship_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE queue_technology_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE resource_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ship_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ship_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ship_model_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ship_model_resource_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE solar_system_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE technology_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE technology_resource_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE technology_type_id_seq CASCADE');
        $this->addSql('ALTER TABLE building DROP CONSTRAINT FK_E16F61D4C54C8C93');
        $this->addSql('ALTER TABLE building_resource DROP CONSTRAINT FK_671DF54D4D2A7E12');
        $this->addSql('ALTER TABLE building_resource DROP CONSTRAINT FK_671DF54D89329D25');
        $this->addSql('ALTER TABLE fleet DROP CONSTRAINT FK_A05E1E477E3C61F9');
        $this->addSql('ALTER TABLE guild_rank DROP CONSTRAINT FK_692C4E4B5F2131EF');
        $this->addSql('ALTER TABLE planet DROP CONSTRAINT FK_68136AA57E3C61F9');
        $this->addSql('ALTER TABLE planet DROP CONSTRAINT FK_68136AA5E5C8C6D3');
        $this->addSql('ALTER TABLE planet_buildings DROP CONSTRAINT FK_1B9CE95FA25E9820');
        $this->addSql('ALTER TABLE planet_buildings DROP CONSTRAINT FK_1B9CE95F4D2A7E12');
        $this->addSql('ALTER TABLE planet_resource DROP CONSTRAINT FK_CFD8C1C2A25E9820');
        $this->addSql('ALTER TABLE planet_resource DROP CONSTRAINT FK_CFD8C1C289329D25');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A6546ACA910');
        $this->addSql('ALTER TABLE player_technology DROP CONSTRAINT FK_ABC6D67099E6F5DF');
        $this->addSql('ALTER TABLE player_technology DROP CONSTRAINT FK_ABC6D6704235D463');
        $this->addSql('ALTER TABLE queue_building DROP CONSTRAINT FK_20ECFEE46A9BD80F');
        $this->addSql('ALTER TABLE queue_ship DROP CONSTRAINT FK_5F60A3D24A788732');
        $this->addSql('ALTER TABLE queue_ship DROP CONSTRAINT FK_5F60A3D2A25E9820');
        $this->addSql('ALTER TABLE queue_technology DROP CONSTRAINT FK_4B952BE897D5A8D7');
        $this->addSql('ALTER TABLE ship DROP CONSTRAINT FK_FA30EB247975B7E7');
        $this->addSql('ALTER TABLE ship DROP CONSTRAINT FK_FA30EB247E3C61F9');
        $this->addSql('ALTER TABLE ship DROP CONSTRAINT FK_FA30EB244B061DF9');
        $this->addSql('ALTER TABLE ship_model DROP CONSTRAINT FK_D6EC210C12469DE2');
        $this->addSql('ALTER TABLE ship_model DROP CONSTRAINT FK_D6EC210C7E3C61F9');
        $this->addSql('ALTER TABLE ship_model_technology DROP CONSTRAINT FK_21DD55784A788732');
        $this->addSql('ALTER TABLE ship_model_technology DROP CONSTRAINT FK_21DD55784235D463');
        $this->addSql('ALTER TABLE ship_model_resource DROP CONSTRAINT FK_41DE13FB4A788732');
        $this->addSql('ALTER TABLE ship_model_resource DROP CONSTRAINT FK_41DE13FB89329D25');
        $this->addSql('ALTER TABLE solar_system DROP CONSTRAINT FK_28893917B61FAB2');
        $this->addSql('ALTER TABLE technology DROP CONSTRAINT FK_F463524DC54C8C93');
        $this->addSql('ALTER TABLE technology_resource DROP CONSTRAINT FK_9ED2ACEA4235D463');
        $this->addSql('ALTER TABLE technology_resource DROP CONSTRAINT FK_9ED2ACEA89329D25');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE building_resource');
        $this->addSql('DROP TABLE building_type');
        $this->addSql('DROP TABLE fleet');
        $this->addSql('DROP TABLE galaxy');
        $this->addSql('DROP TABLE guild');
        $this->addSql('DROP TABLE guild_rank');
        $this->addSql('DROP TABLE planet');
        $this->addSql('DROP TABLE planet_buildings');
        $this->addSql('DROP TABLE planet_resource');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_technology');
        $this->addSql('DROP TABLE queue_building');
        $this->addSql('DROP TABLE queue_ship');
        $this->addSql('DROP TABLE queue_technology');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE ship');
        $this->addSql('DROP TABLE ship_category');
        $this->addSql('DROP TABLE ship_model');
        $this->addSql('DROP TABLE ship_model_technology');
        $this->addSql('DROP TABLE ship_model_resource');
        $this->addSql('DROP TABLE solar_system');
        $this->addSql('DROP TABLE technology');
        $this->addSql('DROP TABLE technology_resource');
        $this->addSql('DROP TABLE technology_type');
    }
}
