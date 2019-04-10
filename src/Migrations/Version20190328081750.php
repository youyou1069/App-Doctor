<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328081750 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_E00CEDDE6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('ALTER TABLE consultation ADD allergies LONGTEXT DEFAULT NULL, ADD med_family_history LONGTEXT DEFAULT NULL, ADD med_treatment_history LONGTEXT DEFAULT NULL, ADD others LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE medical_history CHANGE allergies allergies LONGTEXT DEFAULT NULL, CHANGE other other LONGTEXT DEFAULT NULL, CHANGE treatment treatment LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD gender VARCHAR(255) NOT NULL, CHANGE birth_at birth_at DATE NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, first_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, phone INT NOT NULL, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('ALTER TABLE consultation DROP allergies, DROP med_family_history, DROP med_treatment_history, DROP others');
        $this->addSql('ALTER TABLE medical_history CHANGE allergies allergies VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE other other VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE treatment treatment VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE patient DROP gender, CHANGE birth_at birth_at DATETIME NOT NULL');
    }
}
