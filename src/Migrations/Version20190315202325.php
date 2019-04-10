<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190315202325 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A687F4FB17');
        $this->addSql('DROP INDEX IDX_964685A687F4FB17 ON consultation');
        $this->addSql('ALTER TABLE consultation DROP doctor_id, CHANGE diagnostic diagnostic LONGTEXT DEFAULT NULL, CHANGE treatment treatment LONGTEXT DEFAULT NULL, CHANGE decision decision LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consultation ADD doctor_id INT DEFAULT NULL, CHANGE diagnostic diagnostic VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE treatment treatment VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE decision decision VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A687F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_964685A687F4FB17 ON consultation (doctor_id)');
    }
}
