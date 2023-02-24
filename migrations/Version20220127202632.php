<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127202632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE light_groups ADD bridge_id INT NOT NULL');
        $this->addSql('ALTER TABLE light_groups ADD CONSTRAINT FK_15BCED094948DF55 FOREIGN KEY (bridge_id) REFERENCES light_bridges (id)');
        $this->addSql('CREATE INDEX IDX_15BCED094948DF55 ON light_groups (bridge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE light_groups DROP FOREIGN KEY FK_15BCED094948DF55');
        $this->addSql('DROP INDEX IDX_15BCED094948DF55 ON light_groups');
        $this->addSql('ALTER TABLE light_groups DROP bridge_id');
    }
}
