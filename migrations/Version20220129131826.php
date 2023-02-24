<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129131826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE light_groups_lights (light_groups_id INT NOT NULL, lights_id INT NOT NULL, INDEX IDX_4C3D5A5C40978CAC (light_groups_id), INDEX IDX_4C3D5A5CD594778D (lights_id), PRIMARY KEY(light_groups_id, lights_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE light_groups_lights ADD CONSTRAINT FK_4C3D5A5C40978CAC FOREIGN KEY (light_groups_id) REFERENCES light_groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE light_groups_lights ADD CONSTRAINT FK_4C3D5A5CD594778D FOREIGN KEY (lights_id) REFERENCES lights (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE light_groups_lights');
    }
}
