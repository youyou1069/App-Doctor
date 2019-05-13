<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506081130 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, doctor_id INT DEFAULT NULL, begin_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_E00CEDDE6B899279 (patient_id), INDEX IDX_E00CEDDE87F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, diagnostic LONGTEXT DEFAULT NULL, treatment LONGTEXT DEFAULT NULL, decision LONGTEXT DEFAULT NULL, allergies LONGTEXT DEFAULT NULL, med_family_history LONGTEXT DEFAULT NULL, med_treatment_history LONGTEXT DEFAULT NULL, others LONGTEXT DEFAULT NULL, INDEX IDX_964685A66B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation_drug (consultation_id INT NOT NULL, drug_id INT NOT NULL, INDEX IDX_6822FAF462FF6CDF (consultation_id), INDEX IDX_6822FAF4AABCA765 (drug_id), PRIMARY KEY(consultation_id, drug_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drug (id INT AUTO_INCREMENT NOT NULL, consultation_id INT DEFAULT NULL, titulaire VARCHAR(255) DEFAULT NULL, code INT DEFAULT NULL, denomination VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, code_atc VARCHAR(255) DEFAULT NULL, INDEX IDX_43EB7A3E62FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medical_history (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, allergies LONGTEXT DEFAULT NULL, other LONGTEXT DEFAULT NULL, treatment LONGTEXT DEFAULT NULL, INDEX IDX_61B890856B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birth_at DATE NOT NULL, nir INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone INT DEFAULT NULL, address VARCHAR(255) NOT NULL, postcode INT NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, gender VARCHAR(255) NOT NULL, INDEX IDX_1ADAD7EB87F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE87F4FB17 FOREIGN KEY (doctor_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A66B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE consultation_drug ADD CONSTRAINT FK_6822FAF462FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE consultation_drug ADD CONSTRAINT FK_6822FAF4AABCA765 FOREIGN KEY (drug_id) REFERENCES drug (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drug ADD CONSTRAINT FK_43EB7A3E62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE medical_history ADD CONSTRAINT FK_61B890856B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB87F4FB17 FOREIGN KEY (doctor_id) REFERENCES fos_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consultation_drug DROP FOREIGN KEY FK_6822FAF462FF6CDF');
        $this->addSql('ALTER TABLE drug DROP FOREIGN KEY FK_43EB7A3E62FF6CDF');
        $this->addSql('ALTER TABLE consultation_drug DROP FOREIGN KEY FK_6822FAF4AABCA765');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE6B899279');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A66B899279');
        $this->addSql('ALTER TABLE medical_history DROP FOREIGN KEY FK_61B890856B899279');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE87F4FB17');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB87F4FB17');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE consultation_drug');
        $this->addSql('DROP TABLE drug');
        $this->addSql('DROP TABLE medical_history');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE fos_user');
    }
}
