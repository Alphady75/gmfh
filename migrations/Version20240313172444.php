<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313172444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booster ADD description LONGTEXT DEFAULT NULL, ADD start_at DATETIME NOT NULL, ADD end_at DATETIME NOT NULL, ADD created DATETIME NOT NULL, ADD updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offre ADD booster_id INT DEFAULT NULL, ADD complet TINYINT(1) NOT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL, CHANGE intitule_post intitule_post VARCHAR(255) DEFAULT NULL, CHANGE localisation localisation VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FF85E4930 FOREIGN KEY (booster_id) REFERENCES booster (id)');
        $this->addSql('CREATE INDEX IDX_AF86866FF85E4930 ON offre (booster_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booster DROP description, DROP start_at, DROP end_at, DROP created, DROP updated');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FF85E4930');
        $this->addSql('DROP INDEX IDX_AF86866FF85E4930 ON offre');
        $this->addSql('ALTER TABLE offre DROP booster_id, DROP complet, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE intitule_post intitule_post VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE localisation localisation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
