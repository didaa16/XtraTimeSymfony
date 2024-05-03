<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425231303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY fk_abonnement_terrian');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY abonnement_ibfk_1');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB18C7DFBE FOREIGN KEY (packId) REFERENCES pack (idP)');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB25011433 FOREIGN KEY (terrainId) REFERENCES terrain (id)');
        $this->addSql('ALTER TABLE complexe DROP FOREIGN KEY fk_complexe');
        $this->addSql('ALTER TABLE complexe CHANGE idlocateur idlocateur VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complexe ADD CONSTRAINT FK_EED29F2F597D9E36 FOREIGN KEY (idlocateur) REFERENCES utilisateurs (pseudo)');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY equipement_ibfk_1');
        $this->addSql('ALTER TABLE equipement CHANGE terrainId terrainId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F325011433 FOREIGN KEY (terrainId) REFERENCES terrain (id)');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY fk_user');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY event_ibfk_1');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY fk_terrain');
        $this->addSql('ALTER TABLE event CHANGE idterrain idterrain INT DEFAULT NULL, CHANGE idsponso idsponso INT DEFAULT NULL, CHANGE iduser iduser VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA75908C42C FOREIGN KEY (idsponso) REFERENCES sponso (idsponso)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7177201DF FOREIGN KEY (idterrain) REFERENCES terrain (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA75E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateurs (pseudo)');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_2');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_1');
        $this->addSql('ALTER TABLE participation CHANGE iduser iduser VARCHAR(255) DEFAULT NULL, CHANGE idevent idevent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F5E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateurs (pseudo)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FEDAB66BE FOREIGN KEY (idevent) REFERENCES event (idevent)');
        $this->addSql('ALTER TABLE pdp DROP FOREIGN KEY fk_img');
        $this->addSql('ALTER TABLE pdp ADD CONSTRAINT FK_FA246EDD473B6946 FOREIGN KEY (pseudoU) REFERENCES utilisateurs (pseudo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB18C7DFBE');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB25011433');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT fk_abonnement_terrian FOREIGN KEY (terrainId) REFERENCES terrain (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT abonnement_ibfk_1 FOREIGN KEY (packId) REFERENCES pack (idP) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE complexe DROP FOREIGN KEY FK_EED29F2F597D9E36');
        $this->addSql('ALTER TABLE complexe CHANGE idlocateur idlocateur VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE complexe ADD CONSTRAINT fk_complexe FOREIGN KEY (idlocateur) REFERENCES utilisateurs (pseudo) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F325011433');
        $this->addSql('ALTER TABLE equipement CHANGE terrainId terrainId INT NOT NULL');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT equipement_ibfk_1 FOREIGN KEY (terrainId) REFERENCES terrain (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA75908C42C');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7177201DF');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA75E5C27E9');
        $this->addSql('ALTER TABLE event CHANGE idsponso idsponso INT NOT NULL, CHANGE idterrain idterrain INT NOT NULL, CHANGE iduser iduser VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT fk_user FOREIGN KEY (iduser) REFERENCES utilisateurs (pseudo) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT event_ibfk_1 FOREIGN KEY (idsponso) REFERENCES sponso (idsponso) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT fk_terrain FOREIGN KEY (idterrain) REFERENCES terrain (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F5E5C27E9');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FEDAB66BE');
        $this->addSql('ALTER TABLE participation CHANGE iduser iduser VARCHAR(255) NOT NULL, CHANGE idevent idevent INT NOT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT participation_ibfk_2 FOREIGN KEY (iduser) REFERENCES utilisateurs (pseudo) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT participation_ibfk_1 FOREIGN KEY (idevent) REFERENCES event (idevent) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pdp DROP FOREIGN KEY FK_FA246EDD473B6946');
        $this->addSql('ALTER TABLE pdp ADD CONSTRAINT fk_img FOREIGN KEY (pseudoU) REFERENCES utilisateurs (pseudo) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
