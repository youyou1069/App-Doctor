<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328135234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE doctor');
        $this->addSql('ALTER TABLE patient ADD doctor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB87F4FB17 FOREIGN KEY (doctor_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EB87F4FB17 ON patient (doctor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, last_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, first_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, phone INT NOT NULL, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB87F4FB17');
        $this->addSql('DROP INDEX IDX_1ADAD7EB87F4FB17 ON patient');
        $this->addSql('ALTER TABLE patient DROP doctor_id');
    }
}
