<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190316223045 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consultation ADD med_history_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6C4DD7D49 FOREIGN KEY (med_history_id) REFERENCES medical_history (id)');
        $this->addSql('CREATE INDEX IDX_964685A6C4DD7D49 ON consultation (med_history_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6C4DD7D49');
        $this->addSql('DROP INDEX IDX_964685A6C4DD7D49 ON consultation');
        $this->addSql('ALTER TABLE consultation DROP med_history_id');
    }
}
