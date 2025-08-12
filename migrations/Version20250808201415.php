<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250808201415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin CHANGE id_ref id_ref VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD ressource_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressources_humaines (id)');
        $this->addSql('CREATE INDEX IDX_18D2B091FC6CD52A ON materiel (ressource_id)');
        $this->addSql('ALTER TABLE poste ADD image_poste VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin CHANGE id_ref id_ref VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091FC6CD52A');
        $this->addSql('DROP INDEX IDX_18D2B091FC6CD52A ON materiel');
        $this->addSql('ALTER TABLE materiel DROP ressource_id');
        $this->addSql('ALTER TABLE poste DROP image_poste');
    }
}
