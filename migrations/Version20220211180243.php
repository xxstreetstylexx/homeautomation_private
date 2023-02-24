<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211180243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actions (id INT AUTO_INCREMENT NOT NULL, sensor_id INT NOT NULL, mode VARCHAR(255) NOT NULL, operation VARCHAR(255) NOT NULL, value INT NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_548F1EFA247991F (sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actions_lights (actions_id INT NOT NULL, lights_id INT NOT NULL, INDEX IDX_B5F4AA93B15F4BF6 (actions_id), INDEX IDX_B5F4AA93D594778D (lights_id), PRIMARY KEY(actions_id, lights_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actions ADD CONSTRAINT FK_548F1EFA247991F FOREIGN KEY (sensor_id) REFERENCES sensors (id)');
        $this->addSql('ALTER TABLE actions_lights ADD CONSTRAINT FK_B5F4AA93B15F4BF6 FOREIGN KEY (actions_id) REFERENCES actions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actions_lights ADD CONSTRAINT FK_B5F4AA93D594778D FOREIGN KEY (lights_id) REFERENCES lights (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actions_lights DROP FOREIGN KEY FK_B5F4AA93B15F4BF6');
        $this->addSql('DROP TABLE actions');
        $this->addSql('DROP TABLE actions_lights');
    }
}
