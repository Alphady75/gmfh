<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501074417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, stripe_id INT DEFAULT NULL, user_id INT NOT NULL, active TINYINT(1) NOT NULL, annuler TINYINT(1) NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_351268BB3F1B1098 (stripe_id), UNIQUE INDEX UNIQ_351268BBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, raison LONGTEXT NOT NULL, objet VARCHAR(90) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_B8755515A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, query VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, lu TINYINT(1) NOT NULL, lieu_travail VARCHAR(255) DEFAULT NULL, type_contrat VARCHAR(255) DEFAULT NULL, periodicite VARCHAR(255) DEFAULT NULL, min_salaire INT DEFAULT NULL, max_salaire INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_17FD46C1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alert_secteurs_activite (alert_id INT NOT NULL, secteurs_activite_id INT NOT NULL, INDEX IDX_E131A13993035F72 (alert_id), INDEX IDX_E131A139E4D2C943 (secteurs_activite_id), PRIMARY KEY(alert_id, secteurs_activite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, user_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, online TINYINT(1) NOT NULL, resume VARCHAR(255) DEFAULT NULL, complet TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_23A0E66BCF5E72D (categorie_id), INDEX IDX_23A0E66A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, post_id INT DEFAULT NULL, auteur_id INT NOT NULL, offre_id INT DEFAULT NULL, description LONGTEXT NOT NULL, type VARCHAR(90) NOT NULL, note INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_8F91ABF0A76ED395 (user_id), INDEX IDX_8F91ABF04B89032C (post_id), INDEX IDX_8F91ABF060BB6FE6 (auteur_id), INDEX IDX_8F91ABF04CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE boost (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, offre_id INT DEFAULT NULL, element VARCHAR(20) NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, status VARCHAR(15) NOT NULL, token VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_566427684B89032C (post_id), INDEX IDX_566427684CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booster (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, tarif INT NOT NULL, duree VARCHAR(100) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, offre_id INT NOT NULL, cv VARCHAR(255) NOT NULL, statut VARCHAR(20) DEFAULT NULL, etat VARCHAR(15) DEFAULT NULL, token VARCHAR(255) NOT NULL, candidat_presentation LONGTEXT NOT NULL, status VARCHAR(30) DEFAULT NULL, entretien VARCHAR(90) DEFAULT NULL, lettre_motivation VARCHAR(255) DEFAULT NULL, date_entretien DATETIME DEFAULT NULL, date_selection DATETIME DEFAULT NULL, date_trie DATETIME DEFAULT NULL, entretien_message LONGTEXT DEFAULT NULL, evaluation_message LONGTEXT DEFAULT NULL, date_evaluation DATETIME DEFAULT NULL, selection_message LONGTEXT DEFAULT NULL, date_rejet DATETIME DEFAULT NULL, rejet_message LONGTEXT DEFAULT NULL, acceptation_date DATETIME DEFAULT NULL, acceptation_message LONGTEXT DEFAULT NULL, status_color VARCHAR(10) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_E33BD3B8A76ED395 (user_id), INDEX IDX_E33BD3B84CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(90) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, complet TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom VARCHAR(90) NOT NULL, email VARCHAR(90) NOT NULL, contenu VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_9474526C7294869C (article_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_94D4687FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composants (id INT AUTO_INCREMENT NOT NULL, stripe_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_F95A31993F1B1098 (stripe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, candidature_id INT DEFAULT NULL, user1_id INT NOT NULL, user2_id INT DEFAULT NULL, last_message_id INT DEFAULT NULL, sender_id INT DEFAULT NULL, post_id INT DEFAULT NULL, terminee TINYINT(1) NOT NULL, token VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_8A8E26E9B6121583 (candidature_id), INDEX IDX_8A8E26E956AE248B (user1_id), INDEX IDX_8A8E26E9441B8B65 (user2_id), INDEX IDX_8A8E26E9BA0E79C3 (last_message_id), INDEX IDX_8A8E26E9F624B39D (sender_id), INDEX IDX_8A8E26E94B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etude (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, annee VARCHAR(255) NOT NULL, ecole VARCHAR(255) NOT NULL, diplome VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_1DDEA924A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, entreprise VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_590C103A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experiences (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_82020E704CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, offre_id INT DEFAULT NULL, post_id INT DEFAULT NULL, user_id INT DEFAULT NULL, element VARCHAR(15) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_8933C4324CC8505A (offre_id), INDEX IDX_8933C4324B89032C (post_id), INDEX IDX_8933C432A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE horaires (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, name VARCHAR(100) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_39B7118F4CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE langue (id INT AUTO_INCREMENT NOT NULL, offre_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(90) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_9357758E4CC8505A (offre_id), INDEX IDX_9357758EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, image VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_6A2CA10C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, destinataire_id INT DEFAULT NULL, candidature_id INT DEFAULT NULL, conversation_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, lu TINYINT(1) NOT NULL, fichier VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_B6BD307F60BB6FE6 (auteur_id), INDEX IDX_B6BD307FA4F84F6E (destinataire_id), INDEX IDX_B6BD307FB6121583 (candidature_id), INDEX IDX_B6BD307F9AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, secteuractivite_id INT DEFAULT NULL, soussecteuractivite_id INT DEFAULT NULL, booster_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, intitule_poste VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, infos_contrat LONGTEXT DEFAULT NULL, effectif_recrutement INT DEFAULT NULL, lieu_travail VARCHAR(100) DEFAULT NULL, type_contrat VARCHAR(200) DEFAULT NULL, complet TINYINT(1) NOT NULL, boosted TINYINT(1) DEFAULT NULL, status VARCHAR(20) DEFAULT NULL, annee_experience VARCHAR(100) DEFAULT NULL, salaire INT DEFAULT NULL, qualification VARCHAR(255) DEFAULT NULL, periodicite VARCHAR(90) DEFAULT NULL, date_fin DATETIME DEFAULT NULL, devise VARCHAR(20) DEFAULT NULL, genre VARCHAR(90) DEFAULT NULL, bloquer TINYINT(1) DEFAULT NULL, bloquer_at DATETIME DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_AF86866FA76ED395 (user_id), INDEX IDX_AF86866FC84BB3BA (secteuractivite_id), INDEX IDX_AF86866FC5CBD0AD (soussecteuractivite_id), INDEX IDX_AF86866FF85E4930 (booster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, souscategorie_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, booster_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, tarif INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, online TINYINT(1) NOT NULL, etat VARCHAR(30) DEFAULT NULL, negociable TINYINT(1) DEFAULT NULL, livraison TINYINT(1) NOT NULL, promo TINYINT(1) NOT NULL, vedette TINYINT(1) DEFAULT NULL, statut VARCHAR(90) DEFAULT NULL, tarif_promo INT DEFAULT NULL, boosted TINYINT(1) NOT NULL, devise VARCHAR(30) DEFAULT NULL, urgent TINYINT(1) DEFAULT NULL, is_selled TINYINT(1) DEFAULT NULL, sell_plateform VARCHAR(255) DEFAULT NULL, bloquer TINYINT(1) DEFAULT NULL, bloquer_at DATETIME DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, image_deux VARCHAR(255) DEFAULT NULL, image_trois VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8DA27126E0 (souscategorie_id), INDEX IDX_5A8A6C8DBCF5E72D (categorie_id), INDEX IDX_5A8A6C8DF85E4930 (booster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_EAA5610EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsabilite (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_4EA098204CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secteurs_activite (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, complet TINYINT(1) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_E19D9AD2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signaler (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, offre_id INT DEFAULT NULL, post_id INT DEFAULT NULL, abus VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_EF69B32A76ED395 (user_id), INDEX IDX_EF69B324CC8505A (offre_id), INDEX IDX_EF69B324B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_categorie (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, name VARCHAR(90) NOT NULL, slug VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_52743D7BBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_secteurs_activite (id INT AUTO_INCREMENT NOT NULL, secteurs_activite_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_B8C5BC33E4D2C943 (secteurs_activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stripe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, tarif DOUBLE PRECISION DEFAULT NULL, stripe_key VARCHAR(255) DEFAULT NULL, hexa_color VARCHAR(7) DEFAULT NULL, description LONGTEXT DEFAULT NULL, type_tarification VARCHAR(30) DEFAULT NULL, recommanded TINYINT(1) DEFAULT NULL, complet TINYINT(1) DEFAULT NULL, devise VARCHAR(10) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_389B7837294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, secteuractivite_id INT DEFAULT NULL, soussecteuractivite_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, telephone VARCHAR(30) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, code_is_verified INT DEFAULT NULL, reset_password_code INT DEFAULT NULL, compte VARCHAR(20) DEFAULT NULL, nom VARCHAR(90) DEFAULT NULL, prenom VARCHAR(90) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, niu VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, nom_responsable VARCHAR(100) DEFAULT NULL, site_web VARCHAR(255) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, completed TINYINT(1) NOT NULL, apropo LONGTEXT DEFAULT NULL, name_slug VARCHAR(255) NOT NULL, societe VARCHAR(255) DEFAULT NULL, genre VARCHAR(30) DEFAULT NULL, qualification VARCHAR(255) DEFAULT NULL, annee_experience INT DEFAULT NULL, salaire INT DEFAULT NULL, periodicite VARCHAR(90) DEFAULT NULL, devise VARCHAR(30) DEFAULT NULL, annuaire TINYINT(1) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, youtube VARCHAR(255) DEFAULT NULL, pinterest VARCHAR(255) DEFAULT NULL, tumblr VARCHAR(255) DEFAULT NULL, whatsapp VARCHAR(255) DEFAULT NULL, siege VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649C84BB3BA (secteuractivite_id), INDEX IDX_8D93D649C5CBD0AD (soussecteuractivite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(90) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_43C3D9C3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vue (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, offre_id INT DEFAULT NULL, ip VARCHAR(60) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_C0ADD594A76ED395 (user_id), INDEX IDX_C0ADD5944CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB3F1B1098 FOREIGN KEY (stripe_id) REFERENCES stripe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alert_secteurs_activite ADD CONSTRAINT FK_E131A13993035F72 FOREIGN KEY (alert_id) REFERENCES alert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alert_secteurs_activite ADD CONSTRAINT FK_E131A139E4D2C943 FOREIGN KEY (secteurs_activite_id) REFERENCES secteurs_activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES article_categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF04B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF060BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF04CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE boost ADD CONSTRAINT FK_566427684B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE boost ADD CONSTRAINT FK_566427684CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B84CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competence ADD CONSTRAINT FK_94D4687FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE composants ADD CONSTRAINT FK_F95A31993F1B1098 FOREIGN KEY (stripe_id) REFERENCES stripe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9B6121583 FOREIGN KEY (candidature_id) REFERENCES candidature (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E956AE248B FOREIGN KEY (user1_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9441B8B65 FOREIGN KEY (user2_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9BA0E79C3 FOREIGN KEY (last_message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9F624B39D FOREIGN KEY (sender_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E94B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etude ADD CONSTRAINT FK_1DDEA924A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E704CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C4324CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C4324B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE horaires ADD CONSTRAINT FK_39B7118F4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE langue ADD CONSTRAINT FK_9357758E4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE langue ADD CONSTRAINT FK_9357758EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB6121583 FOREIGN KEY (candidature_id) REFERENCES candidature (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FC84BB3BA FOREIGN KEY (secteuractivite_id) REFERENCES secteurs_activite (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FC5CBD0AD FOREIGN KEY (soussecteuractivite_id) REFERENCES sous_secteurs_activite (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FF85E4930 FOREIGN KEY (booster_id) REFERENCES booster (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA27126E0 FOREIGN KEY (souscategorie_id) REFERENCES sous_categorie (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF85E4930 FOREIGN KEY (booster_id) REFERENCES booster (id)');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE responsabilite ADD CONSTRAINT FK_4EA098204CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signaler ADD CONSTRAINT FK_EF69B32A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signaler ADD CONSTRAINT FK_EF69B324CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signaler ADD CONSTRAINT FK_EF69B324B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sous_secteurs_activite ADD CONSTRAINT FK_B8C5BC33E4D2C943 FOREIGN KEY (secteurs_activite_id) REFERENCES secteurs_activite (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7837294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C84BB3BA FOREIGN KEY (secteuractivite_id) REFERENCES secteurs_activite (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C5CBD0AD FOREIGN KEY (soussecteuractivite_id) REFERENCES sous_secteurs_activite (id)');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vue ADD CONSTRAINT FK_C0ADD594A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vue ADD CONSTRAINT FK_C0ADD5944CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert_secteurs_activite DROP FOREIGN KEY FK_E131A13993035F72');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B7837294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BCF5E72D');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FF85E4930');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF85E4930');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9B6121583');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB6121583');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DBCF5E72D');
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7BBCF5E72D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9BA0E79C3');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF04CC8505A');
        $this->addSql('ALTER TABLE boost DROP FOREIGN KEY FK_566427684CC8505A');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B84CC8505A');
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E704CC8505A');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C4324CC8505A');
        $this->addSql('ALTER TABLE horaires DROP FOREIGN KEY FK_39B7118F4CC8505A');
        $this->addSql('ALTER TABLE langue DROP FOREIGN KEY FK_9357758E4CC8505A');
        $this->addSql('ALTER TABLE responsabilite DROP FOREIGN KEY FK_4EA098204CC8505A');
        $this->addSql('ALTER TABLE signaler DROP FOREIGN KEY FK_EF69B324CC8505A');
        $this->addSql('ALTER TABLE vue DROP FOREIGN KEY FK_C0ADD5944CC8505A');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF04B89032C');
        $this->addSql('ALTER TABLE boost DROP FOREIGN KEY FK_566427684B89032C');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E94B89032C');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C4324B89032C');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C4B89032C');
        $this->addSql('ALTER TABLE signaler DROP FOREIGN KEY FK_EF69B324B89032C');
        $this->addSql('ALTER TABLE alert_secteurs_activite DROP FOREIGN KEY FK_E131A139E4D2C943');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FC84BB3BA');
        $this->addSql('ALTER TABLE sous_secteurs_activite DROP FOREIGN KEY FK_B8C5BC33E4D2C943');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C84BB3BA');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA27126E0');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FC5CBD0AD');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C5CBD0AD');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB3F1B1098');
        $this->addSql('ALTER TABLE composants DROP FOREIGN KEY FK_F95A31993F1B1098');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBA76ED395');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515A76ED395');
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C1A76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A76ED395');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF060BB6FE6');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8A76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE competence DROP FOREIGN KEY FK_94D4687FA76ED395');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E956AE248B');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9441B8B65');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9F624B39D');
        $this->addSql('ALTER TABLE etude DROP FOREIGN KEY FK_1DDEA924A76ED395');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103A76ED395');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A76ED395');
        $this->addSql('ALTER TABLE langue DROP FOREIGN KEY FK_9357758EA76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F60BB6FE6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA4F84F6E');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE realisation DROP FOREIGN KEY FK_EAA5610EA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2A76ED395');
        $this->addSql('ALTER TABLE signaler DROP FOREIGN KEY FK_EF69B32A76ED395');
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C3A76ED395');
        $this->addSql('ALTER TABLE vue DROP FOREIGN KEY FK_C0ADD594A76ED395');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE alert_secteurs_activite');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_categorie');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE boost');
        $this->addSql('DROP TABLE booster');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE composants');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE devise');
        $this->addSql('DROP TABLE etude');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE experiences');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE horaires');
        $this->addSql('DROP TABLE langue');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE responsabilite');
        $this->addSql('DROP TABLE secteurs_activite');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE signaler');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('DROP TABLE sous_secteurs_activite');
        $this->addSql('DROP TABLE stripe');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE vue');
    }
}
