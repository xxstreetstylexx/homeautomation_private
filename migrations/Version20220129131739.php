<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129131739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lights DROP FOREIGN KEY FK_38BCB2E840978CAC');
        $this->addSql('DROP INDEX IDX_38BCB2E840978CAC ON lights');
        $this->addSql('ALTER TABLE lights DROP light_groups_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lights ADD light_groups_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lights ADD CONSTRAINT FK_38BCB2E840978CAC FOREIGN KEY (light_groups_id) REFERENCES light_groups (id)');
        $this->addSql('CREATE INDEX IDX_38BCB2E840978CAC ON lights (light_groups_id)');
    }
}
