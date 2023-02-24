<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220205232632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE scenes_light_groups');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scenes_light_groups (scenes_id INT NOT NULL, light_groups_id INT NOT NULL, INDEX IDX_35719FB82B6BAAE3 (scenes_id), INDEX IDX_35719FB840978CAC (light_groups_id), PRIMARY KEY(scenes_id, light_groups_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE scenes_light_groups ADD CONSTRAINT FK_35719FB840978CAC FOREIGN KEY (light_groups_id) REFERENCES light_groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scenes_light_groups ADD CONSTRAINT FK_35719FB82B6BAAE3 FOREIGN KEY (scenes_id) REFERENCES scenes (id) ON DELETE CASCADE');
    }
}
