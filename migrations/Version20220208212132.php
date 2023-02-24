<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220208212132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sensors (id INT AUTO_INCREMENT NOT NULL, bridge_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, internal_id INT NOT NULL, reachable TINYINT(1) NOT NULL, uniqueid VARCHAR(255) NOT NULL, state LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', battery INT DEFAULT NULL, INDEX IDX_D0D3FA904948DF55 (bridge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sensors ADD CONSTRAINT FK_D0D3FA904948DF55 FOREIGN KEY (bridge_id) REFERENCES light_bridges (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sensors');
    }
}
