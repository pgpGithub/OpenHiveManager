<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151105104344 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hausse DROP FOREIGN KEY FK_C2AB4B2CC9EF1A8B');
        $this->addSql('DROP TABLE hausse');
        $this->addSql('DROP TABLE provenance_reine');
        $this->addSql('DROP TABLE recolte_ruche');
        $this->addSql('DROP TABLE sujet');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hausse (id INT AUTO_INCREMENT NOT NULL, ruche_id INT DEFAULT NULL, type_id INT NOT NULL, recolteruche_id INT DEFAULT NULL, nbplein INT NOT NULL, nbcadres INT NOT NULL, INDEX IDX_C2AB4B2C87DDEC63 (ruche_id), INDEX IDX_C2AB4B2CC54C8C93 (type_id), INDEX IDX_C2AB4B2CC9EF1A8B (recolteruche_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provenance_reine (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(25) NOT NULL, UNIQUE INDEX UNIQ_31F0715EA4D60759 (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recolte_ruche (id INT AUTO_INCREMENT NOT NULL, colonie_id INT NOT NULL, recolterucher_id INT NOT NULL, INDEX IDX_3F72ED2B931E708D (colonie_id), INDEX IDX_3F72ED2B256E6FF2 (recolterucher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sujet (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(80) NOT NULL, UNIQUE INDEX UNIQ_2E13599DA4D60759 (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hausse ADD CONSTRAINT FK_C2AB4B2C87DDEC63 FOREIGN KEY (ruche_id) REFERENCES ruche (id)');
        $this->addSql('ALTER TABLE hausse ADD CONSTRAINT FK_C2AB4B2CC54C8C93 FOREIGN KEY (type_id) REFERENCES type_ruche (id)');
        $this->addSql('ALTER TABLE hausse ADD CONSTRAINT FK_C2AB4B2CC9EF1A8B FOREIGN KEY (recolteruche_id) REFERENCES recolte_ruche (id)');
    }
}
