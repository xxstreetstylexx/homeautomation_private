<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121003318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE light_bridges (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, account VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE light_log (id INT AUTO_INCREMENT NOT NULL, light_id INT NOT NULL, datetime DATETIME NOT NULL, state_on TINYINT(1) NOT NULL, state_bri INT NOT NULL, state_hue INT NOT NULL, state_sat INT NOT NULL, state_xy LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', reachable TINYINT(1) NOT NULL, INDEX IDX_D60ECAFE3DA64B2C (light_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lights (id INT AUTO_INCREMENT NOT NULL, bridge_id INT NOT NULL, internal_id INT NOT NULL, factory VARCHAR(255) DEFAULT NULL, model VARCHAR(255) NOT NULL, checktime DATETIME DEFAULT NULL, state_on TINYINT(1) DEFAULT NULL, state_bri INT NOT NULL, state_hue INT NOT NULL, state_sat INT NOT NULL, state_xy LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', reachable TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, uniqueid VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_38BCB2E84948DF55 (bridge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE light_log ADD CONSTRAINT FK_D60ECAFE3DA64B2C FOREIGN KEY (light_id) REFERENCES lights (id)');
        $this->addSql('ALTER TABLE lights ADD CONSTRAINT FK_38BCB2E84948DF55 FOREIGN KEY (bridge_id) REFERENCES light_bridges (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lights DROP FOREIGN KEY FK_38BCB2E84948DF55');
        $this->addSql('ALTER TABLE light_log DROP FOREIGN KEY FK_D60ECAFE3DA64B2C');
        $this->addSql('DROP TABLE light_bridges');
        $this->addSql('DROP TABLE light_log');
        $this->addSql('DROP TABLE lights');
    }
}
