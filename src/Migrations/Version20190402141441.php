<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402141441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE drug (id INT AUTO_INCREMENT NOT NULL, consultation_id INT DEFAULT NULL, titulaire VARCHAR(255) DEFAULT NULL, code INT DEFAULT NULL, denomination VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, code_atc VARCHAR(255) DEFAULT NULL, INDEX IDX_43EB7A3E62FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE drug ADD CONSTRAINT FK_43EB7A3E62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE booking ADD doctor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE87F4FB17 FOREIGN KEY (doctor_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE87F4FB17 ON booking (doctor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE drug');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE87F4FB17');
        $this->addSql('DROP INDEX IDX_E00CEDDE87F4FB17 ON booking');
        $this->addSql('ALTER TABLE booking DROP doctor_id');
    }
}
