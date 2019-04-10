<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190315132655 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consultation ADD doctor_id INT NOT NULL');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A687F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_964685A687F4FB17 ON consultation (doctor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A687F4FB17');
        $this->addSql('DROP INDEX IDX_964685A687F4FB17 ON consultation');
        $this->addSql('ALTER TABLE consultation DROP doctor_id');
    }
}
