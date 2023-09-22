<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922112120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A98260155');
        $this->addSql('DROP INDEX IDX_CD1DE18A98260155 ON department');
        $this->addSql('ALTER TABLE department CHANGE region_id region_erm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A2971434C FOREIGN KEY (region_erm_id) REFERENCES region_erm (id)');
        $this->addSql('CREATE INDEX IDX_CD1DE18A2971434C ON department (region_erm_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A2971434C');
        $this->addSql('DROP INDEX IDX_CD1DE18A2971434C ON department');
        $this->addSql('ALTER TABLE department CHANGE region_erm_id region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A98260155 FOREIGN KEY (region_id) REFERENCES region_erm (id)');
        $this->addSql('CREATE INDEX IDX_CD1DE18A98260155 ON department (region_id)');
    }
}
