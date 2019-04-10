<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190409134818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A676ECDA7C');
        $this->addSql('CREATE TABLE consultation_drug (consultation_id INT NOT NULL, drug_id INT NOT NULL, INDEX IDX_6822FAF462FF6CDF (consultation_id), INDEX IDX_6822FAF4AABCA765 (drug_id), PRIMARY KEY(consultation_id, drug_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consultation_drug ADD CONSTRAINT FK_6822FAF462FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE consultation_drug ADD CONSTRAINT FK_6822FAF4AABCA765 FOREIGN KEY (drug_id) REFERENCES drug (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE drug_med');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6AABCA765');
        $this->addSql('DROP INDEX IDX_964685A6AABCA765 ON consultation');
        $this->addSql('DROP INDEX IDX_964685A676ECDA7C ON consultation');
        $this->addSql('ALTER TABLE consultation DROP drug_id, DROP med_consultation_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE drug_med (id INT AUTO_INCREMENT NOT NULL, medicaments_id INT DEFAULT NULL, traitement VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_BBBDFA21C403D7FB (medicaments_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE drug_med ADD CONSTRAINT FK_BBBDFA21C403D7FB FOREIGN KEY (medicaments_id) REFERENCES drug (id)');
        $this->addSql('DROP TABLE consultation_drug');
        $this->addSql('ALTER TABLE consultation ADD drug_id INT DEFAULT NULL, ADD med_consultation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A676ECDA7C FOREIGN KEY (med_consultation_id) REFERENCES drug_med (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6AABCA765 FOREIGN KEY (drug_id) REFERENCES drug (id)');
        $this->addSql('CREATE INDEX IDX_964685A6AABCA765 ON consultation (drug_id)');
        $this->addSql('CREATE INDEX IDX_964685A676ECDA7C ON consultation (med_consultation_id)');
    }
}
