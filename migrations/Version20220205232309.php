<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220205232309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scenes ADD group_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scenes ADD CONSTRAINT FK_7DD18D2E2F68B530 FOREIGN KEY (group_id_id) REFERENCES light_groups (id)');
        $this->addSql('CREATE INDEX IDX_7DD18D2E2F68B530 ON scenes (group_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scenes DROP FOREIGN KEY FK_7DD18D2E2F68B530');
        $this->addSql('DROP INDEX IDX_7DD18D2E2F68B530 ON scenes');
        $this->addSql('ALTER TABLE scenes DROP group_id_id');
    }
}
