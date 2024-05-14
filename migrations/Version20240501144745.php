<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501144745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vue DROP FOREIGN KEY FK_C0ADD594A76ED395');
        $this->addSql('ALTER TABLE vue DROP FOREIGN KEY FK_C0ADD5944CC8505A');
        $this->addSql('ALTER TABLE vue ADD CONSTRAINT FK_C0ADD594A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vue ADD CONSTRAINT FK_C0ADD5944CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vue DROP FOREIGN KEY FK_C0ADD594A76ED395');
        $this->addSql('ALTER TABLE vue DROP FOREIGN KEY FK_C0ADD5944CC8505A');
        $this->addSql('ALTER TABLE vue ADD CONSTRAINT FK_C0ADD594A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vue ADD CONSTRAINT FK_C0ADD5944CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
    }
}
