<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402084525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE drug ADD consultation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE drug ADD CONSTRAINT FK_43EB7A3E62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('CREATE INDEX IDX_43EB7A3E62FF6CDF ON drug (consultation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE drug DROP FOREIGN KEY FK_43EB7A3E62FF6CDF');
        $this->addSql('DROP INDEX IDX_43EB7A3E62FF6CDF ON drug');
        $this->addSql('ALTER TABLE drug DROP consultation_id');
    }
}
