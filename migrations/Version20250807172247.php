<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250807172247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE effect ADD id_ref_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE effect ADD CONSTRAINT FK_B66091F2780F444 FOREIGN KEY (id_ref_id) REFERENCES ressources_humaines (id)');
        $this->addSql('CREATE INDEX IDX_B66091F2780F444 ON effect (id_ref_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE effect DROP FOREIGN KEY FK_B66091F2780F444');
        $this->addSql('DROP INDEX IDX_B66091F2780F444 ON effect');
        $this->addSql('ALTER TABLE effect DROP id_ref_id');
    }
}
