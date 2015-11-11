<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151111083009 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE hausse');
        $this->addSql('DROP TABLE provenance');
        $this->addSql('DROP TABLE provenance_reine');
        $this->addSql('DROP TABLE sujet');
        $this->addSql('ALTER TABLE colonie ADD CONSTRAINT FK_2AB9EA83D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE colonie ADD CONSTRAINT FK_2AB9EA837806720B FOREIGN KEY (agressivite_id) REFERENCES agressivite (id)');
        $this->addSql('CREATE INDEX IDX_2AB9EA83D5E86FF ON colonie (etat_id)');
        $this->addSql('CREATE INDEX IDX_2AB9EA837806720B ON colonie (agressivite_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hausse (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, nbplein INT NOT NULL, nbcadres INT NOT NULL, INDEX IDX_C2AB4B2CC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provenance (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(25) NOT NULL, UNIQUE INDEX UNIQ_8105DD81A4D60759 (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provenance_reine (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(25) NOT NULL, UNIQUE INDEX UNIQ_31F0715EA4D60759 (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sujet (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(80) NOT NULL, UNIQUE INDEX UNIQ_2E13599DA4D60759 (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hausse ADD CONSTRAINT FK_C2AB4B2CC54C8C93 FOREIGN KEY (type_id) REFERENCES type_ruche (id)');
        $this->addSql('ALTER TABLE colonie DROP FOREIGN KEY FK_2AB9EA83D5E86FF');
        $this->addSql('ALTER TABLE colonie DROP FOREIGN KEY FK_2AB9EA837806720B');
        $this->addSql('DROP INDEX IDX_2AB9EA83D5E86FF ON colonie');
        $this->addSql('DROP INDEX IDX_2AB9EA837806720B ON colonie');
    }
}
