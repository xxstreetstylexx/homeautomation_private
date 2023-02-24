<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211211022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actions_light (lights_id INT NOT NULL, actions_id INT NOT NULL, INDEX IDX_2B0FD2E8D594778D (lights_id), INDEX IDX_2B0FD2E8B15F4BF6 (actions_id), PRIMARY KEY(lights_id, actions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actions_light ADD CONSTRAINT FK_2B0FD2E8D594778D FOREIGN KEY (lights_id) REFERENCES lights (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actions_light ADD CONSTRAINT FK_2B0FD2E8B15F4BF6 FOREIGN KEY (actions_id) REFERENCES actions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE actions_light');
    }
}
