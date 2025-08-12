<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250804113358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressources_humaines ADD id_ref_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressources_humaines ADD CONSTRAINT FK_4C1418780F444 FOREIGN KEY (id_ref_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_4C1418780F444 ON ressources_humaines (id_ref_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressources_humaines DROP FOREIGN KEY FK_4C1418780F444');
        $this->addSql('DROP INDEX IDX_4C1418780F444 ON ressources_humaines');
        $this->addSql('ALTER TABLE ressources_humaines DROP id_ref_id');
    }
}
