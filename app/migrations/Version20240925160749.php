<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925160749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_E16F61D4C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building_resource (id INT AUTO_INCREMENT NOT NULL, building_id INT NOT NULL, resource_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_671DF54D4D2A7E12 (building_id), INDEX IDX_671DF54D89329D25 (resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fleet (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, position_x INT NOT NULL, position_y INT NOT NULL, name VARCHAR(255) NOT NULL, destination_x INT DEFAULT NULL, destination_y INT DEFAULT NULL, departure_time DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', arrival_time DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_moving TINYINT(1) NOT NULL, INDEX IDX_A05E1E477E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galaxy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guild (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guild_rank (id INT AUTO_INCREMENT NOT NULL, guild_id INT NOT NULL, name VARCHAR(255) NOT NULL, `rank` INT NOT NULL, INDEX IDX_692C4E4B5F2131EF (guild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, solar_system_id INT NOT NULL, name VARCHAR(255) NOT NULL, position_x INT NOT NULL, position_y INT NOT NULL, INDEX IDX_68136AA57E3C61F9 (owner_id), INDEX IDX_68136AA5E5C8C6D3 (solar_system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_buildings (id INT AUTO_INCREMENT NOT NULL, planet_id INT NOT NULL, building_id INT NOT NULL, level INT NOT NULL, INDEX IDX_1B9CE95FA25E9820 (planet_id), INDEX IDX_1B9CE95F4D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_resource (id INT AUTO_INCREMENT NOT NULL, planet_id INT NOT NULL, resource_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_CFD8C1C2A25E9820 (planet_id), INDEX IDX_CFD8C1C289329D25 (resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, guild_rank_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_98197A6546ACA910 (guild_rank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_technology (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, technology_id INT NOT NULL, level INT NOT NULL, INDEX IDX_ABC6D67099E6F5DF (player_id), INDEX IDX_ABC6D6704235D463 (technology_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE queue_building (id INT AUTO_INCREMENT NOT NULL, planet_building_id INT NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', finished_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_20ECFEE46A9BD80F (planet_building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE queue_ship (id INT AUTO_INCREMENT NOT NULL, ship_model_id INT NOT NULL, planet_id INT NOT NULL, quantity INT NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', finished_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5F60A3D24A788732 (ship_model_id), INDEX IDX_5F60A3D2A25E9820 (planet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE queue_technology (id INT AUTO_INCREMENT NOT NULL, player_technology_id INT NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', finished_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4B952BE897D5A8D7 (player_technology_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, coef DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ship (id INT AUTO_INCREMENT NOT NULL, model_id INT NOT NULL, owner_id INT NOT NULL, fleet_id INT NOT NULL, INDEX IDX_FA30EB247975B7E7 (model_id), INDEX IDX_FA30EB247E3C61F9 (owner_id), INDEX IDX_FA30EB244B061DF9 (fleet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ship_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ship_model (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, speed DOUBLE PRECISION NOT NULL, INDEX IDX_D6EC210C12469DE2 (category_id), INDEX IDX_D6EC210C7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ship_model_technology (ship_model_id INT NOT NULL, technology_id INT NOT NULL, INDEX IDX_21DD55784A788732 (ship_model_id), INDEX IDX_21DD55784235D463 (technology_id), PRIMARY KEY(ship_model_id, technology_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ship_model_resource (id INT AUTO_INCREMENT NOT NULL, ship_model_id INT NOT NULL, resource_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_41DE13FB4A788732 (ship_model_id), INDEX IDX_41DE13FB89329D25 (resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solar_system (id INT AUTO_INCREMENT NOT NULL, galaxy_id INT NOT NULL, name VARCHAR(255) NOT NULL, position_x INT NOT NULL, position_y INT NOT NULL, INDEX IDX_28893917B61FAB2 (galaxy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_F463524DC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_resource (id INT AUTO_INCREMENT NOT NULL, technology_id INT NOT NULL, resource_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_9ED2ACEA4235D463 (technology_id), INDEX IDX_9ED2ACEA89329D25 (resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D4C54C8C93 FOREIGN KEY (type_id) REFERENCES building_type (id)');
        $this->addSql('ALTER TABLE building_resource ADD CONSTRAINT FK_671DF54D4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE building_resource ADD CONSTRAINT FK_671DF54D89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE fleet ADD CONSTRAINT FK_A05E1E477E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE guild_rank ADD CONSTRAINT FK_692C4E4B5F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA57E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA5E5C8C6D3 FOREIGN KEY (solar_system_id) REFERENCES solar_system (id)');
        $this->addSql('ALTER TABLE planet_buildings ADD CONSTRAINT FK_1B9CE95FA25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE planet_buildings ADD CONSTRAINT FK_1B9CE95F4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE planet_resource ADD CONSTRAINT FK_CFD8C1C2A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE planet_resource ADD CONSTRAINT FK_CFD8C1C289329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6546ACA910 FOREIGN KEY (guild_rank_id) REFERENCES guild_rank (id)');
        $this->addSql('ALTER TABLE player_technology ADD CONSTRAINT FK_ABC6D67099E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE player_technology ADD CONSTRAINT FK_ABC6D6704235D463 FOREIGN KEY (technology_id) REFERENCES technology (id)');
        $this->addSql('ALTER TABLE queue_building ADD CONSTRAINT FK_20ECFEE46A9BD80F FOREIGN KEY (planet_building_id) REFERENCES planet_buildings (id)');
        $this->addSql('ALTER TABLE queue_ship ADD CONSTRAINT FK_5F60A3D24A788732 FOREIGN KEY (ship_model_id) REFERENCES ship_model (id)');
        $this->addSql('ALTER TABLE queue_ship ADD CONSTRAINT FK_5F60A3D2A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE queue_technology ADD CONSTRAINT FK_4B952BE897D5A8D7 FOREIGN KEY (player_technology_id) REFERENCES player_technology (id)');
        $this->addSql('ALTER TABLE ship ADD CONSTRAINT FK_FA30EB247975B7E7 FOREIGN KEY (model_id) REFERENCES ship_model (id)');
        $this->addSql('ALTER TABLE ship ADD CONSTRAINT FK_FA30EB247E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE ship ADD CONSTRAINT FK_FA30EB244B061DF9 FOREIGN KEY (fleet_id) REFERENCES fleet (id)');
        $this->addSql('ALTER TABLE ship_model ADD CONSTRAINT FK_D6EC210C12469DE2 FOREIGN KEY (category_id) REFERENCES ship_category (id)');
        $this->addSql('ALTER TABLE ship_model ADD CONSTRAINT FK_D6EC210C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE ship_model_technology ADD CONSTRAINT FK_21DD55784A788732 FOREIGN KEY (ship_model_id) REFERENCES ship_model (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ship_model_technology ADD CONSTRAINT FK_21DD55784235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ship_model_resource ADD CONSTRAINT FK_41DE13FB4A788732 FOREIGN KEY (ship_model_id) REFERENCES ship_model (id)');
        $this->addSql('ALTER TABLE ship_model_resource ADD CONSTRAINT FK_41DE13FB89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE solar_system ADD CONSTRAINT FK_28893917B61FAB2 FOREIGN KEY (galaxy_id) REFERENCES galaxy (id)');
        $this->addSql('ALTER TABLE technology ADD CONSTRAINT FK_F463524DC54C8C93 FOREIGN KEY (type_id) REFERENCES technology_type (id)');
        $this->addSql('ALTER TABLE technology_resource ADD CONSTRAINT FK_9ED2ACEA4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id)');
        $this->addSql('ALTER TABLE technology_resource ADD CONSTRAINT FK_9ED2ACEA89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D4C54C8C93');
        $this->addSql('ALTER TABLE building_resource DROP FOREIGN KEY FK_671DF54D4D2A7E12');
        $this->addSql('ALTER TABLE building_resource DROP FOREIGN KEY FK_671DF54D89329D25');
        $this->addSql('ALTER TABLE fleet DROP FOREIGN KEY FK_A05E1E477E3C61F9');
        $this->addSql('ALTER TABLE guild_rank DROP FOREIGN KEY FK_692C4E4B5F2131EF');
        $this->addSql('ALTER TABLE planet DROP FOREIGN KEY FK_68136AA57E3C61F9');
        $this->addSql('ALTER TABLE planet DROP FOREIGN KEY FK_68136AA5E5C8C6D3');
        $this->addSql('ALTER TABLE planet_buildings DROP FOREIGN KEY FK_1B9CE95FA25E9820');
        $this->addSql('ALTER TABLE planet_buildings DROP FOREIGN KEY FK_1B9CE95F4D2A7E12');
        $this->addSql('ALTER TABLE planet_resource DROP FOREIGN KEY FK_CFD8C1C2A25E9820');
        $this->addSql('ALTER TABLE planet_resource DROP FOREIGN KEY FK_CFD8C1C289329D25');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6546ACA910');
        $this->addSql('ALTER TABLE player_technology DROP FOREIGN KEY FK_ABC6D67099E6F5DF');
        $this->addSql('ALTER TABLE player_technology DROP FOREIGN KEY FK_ABC6D6704235D463');
        $this->addSql('ALTER TABLE queue_building DROP FOREIGN KEY FK_20ECFEE46A9BD80F');
        $this->addSql('ALTER TABLE queue_ship DROP FOREIGN KEY FK_5F60A3D24A788732');
        $this->addSql('ALTER TABLE queue_ship DROP FOREIGN KEY FK_5F60A3D2A25E9820');
        $this->addSql('ALTER TABLE queue_technology DROP FOREIGN KEY FK_4B952BE897D5A8D7');
        $this->addSql('ALTER TABLE ship DROP FOREIGN KEY FK_FA30EB247975B7E7');
        $this->addSql('ALTER TABLE ship DROP FOREIGN KEY FK_FA30EB247E3C61F9');
        $this->addSql('ALTER TABLE ship DROP FOREIGN KEY FK_FA30EB244B061DF9');
        $this->addSql('ALTER TABLE ship_model DROP FOREIGN KEY FK_D6EC210C12469DE2');
        $this->addSql('ALTER TABLE ship_model DROP FOREIGN KEY FK_D6EC210C7E3C61F9');
        $this->addSql('ALTER TABLE ship_model_technology DROP FOREIGN KEY FK_21DD55784A788732');
        $this->addSql('ALTER TABLE ship_model_technology DROP FOREIGN KEY FK_21DD55784235D463');
        $this->addSql('ALTER TABLE ship_model_resource DROP FOREIGN KEY FK_41DE13FB4A788732');
        $this->addSql('ALTER TABLE ship_model_resource DROP FOREIGN KEY FK_41DE13FB89329D25');
        $this->addSql('ALTER TABLE solar_system DROP FOREIGN KEY FK_28893917B61FAB2');
        $this->addSql('ALTER TABLE technology DROP FOREIGN KEY FK_F463524DC54C8C93');
        $this->addSql('ALTER TABLE technology_resource DROP FOREIGN KEY FK_9ED2ACEA4235D463');
        $this->addSql('ALTER TABLE technology_resource DROP FOREIGN KEY FK_9ED2ACEA89329D25');
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
