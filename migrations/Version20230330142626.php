<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330142626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE reset_password_request_id_seq CASCADE');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT fk_7ce748aa76ed395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE formation DROP image');
        $this->addSql('ALTER TABLE formation DROP title_quizz_1');
        $this->addSql('ALTER TABLE formation DROP bool_quizz_1');
        $this->addSql('ALTER TABLE formation DROP title_quizz_2');
        $this->addSql('ALTER TABLE formation DROP bool_quizz_2');
        $this->addSql('ALTER TABLE parcours DROP image');
        $this->addSql('ALTER TABLE parcours DROP chronologie');
        $this->addSql('ALTER TABLE soignant ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" DROP photo_profil');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE reset_password_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE reset_password_request (id INT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_7ce748aa76ed395 ON reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT fk_7ce748aa76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE soignant DROP image');
        $this->addSql('ALTER TABLE "user" ADD photo_profil VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD title_quizz_1 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD bool_quizz_1 BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD title_quizz_2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD bool_quizz_2 BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE parcours ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE parcours ADD chronologie JSON DEFAULT NULL');
    }
}
