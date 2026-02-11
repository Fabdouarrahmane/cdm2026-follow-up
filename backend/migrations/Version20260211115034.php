<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260211115034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2449BA1577153098 (code), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE matche (id INT AUTO_INCREMENT NOT NULL, date_heure DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, phase_id INT NOT NULL, equipe_a_id INT NOT NULL, equipe_b_id INT NOT NULL, INDEX IDX_9FCAD51099091188 (phase_id), INDEX IDX_9FCAD5103297C2A6 (equipe_a_id), INDEX IDX_9FCAD51020226D48 (equipe_b_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE phase (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, ordre INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE score_final (id INT AUTO_INCREMENT NOT NULL, score_a INT NOT NULL, score_b INT NOT NULL, termine_le DATETIME NOT NULL, matche_id INT NOT NULL, UNIQUE INDEX UNIQ_5599904EFD997C2B (matche_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE matche ADD CONSTRAINT FK_9FCAD51099091188 FOREIGN KEY (phase_id) REFERENCES phase (id)');
        $this->addSql('ALTER TABLE matche ADD CONSTRAINT FK_9FCAD5103297C2A6 FOREIGN KEY (equipe_a_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE matche ADD CONSTRAINT FK_9FCAD51020226D48 FOREIGN KEY (equipe_b_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE score_final ADD CONSTRAINT FK_5599904EFD997C2B FOREIGN KEY (matche_id) REFERENCES matche (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matche DROP FOREIGN KEY FK_9FCAD51099091188');
        $this->addSql('ALTER TABLE matche DROP FOREIGN KEY FK_9FCAD5103297C2A6');
        $this->addSql('ALTER TABLE matche DROP FOREIGN KEY FK_9FCAD51020226D48');
        $this->addSql('ALTER TABLE score_final DROP FOREIGN KEY FK_5599904EFD997C2B');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE matche');
        $this->addSql('DROP TABLE phase');
        $this->addSql('DROP TABLE score_final');
    }
}
