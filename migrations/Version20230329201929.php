<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329201929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD title_quizz_1 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD bool_quizz_1 BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD title_quizz_2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD bool_quizz_2 BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE formation DROP title_quizz_1');
        $this->addSql('ALTER TABLE formation DROP bool_quizz_1');
        $this->addSql('ALTER TABLE formation DROP title_quizz_2');
        $this->addSql('ALTER TABLE formation DROP bool_quizz_2');
    }
}
