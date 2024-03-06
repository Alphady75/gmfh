<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305234514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_94D4687FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name_id INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_E19D9AD271179CD6 (name_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competence ADD CONSTRAINT FK_94D4687FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD271179CD6 FOREIGN KEY (name_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(90) DEFAULT NULL, ADD prenom VARCHAR(90) DEFAULT NULL, ADD ville VARCHAR(180) DEFAULT NULL, ADD logo VARCHAR(255) DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL, ADD niu VARCHAR(255) DEFAULT NULL, ADD localisation VARCHAR(255) DEFAULT NULL, ADD nom_responsable VARCHAR(100) DEFAULT NULL, ADD site_web VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE service');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP ville, DROP logo, DROP photo, DROP niu, DROP localisation, DROP nom_responsable, DROP site_web');
    }
}
