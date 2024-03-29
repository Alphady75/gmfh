<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318160500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boost ADD offre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE boost ADD CONSTRAINT FK_566427684CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('CREATE INDEX IDX_566427684CC8505A ON boost (offre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boost DROP FOREIGN KEY FK_566427684CC8505A');
        $this->addSql('DROP INDEX IDX_566427684CC8505A ON boost');
        $this->addSql('ALTER TABLE boost DROP offre_id');
    }
}
