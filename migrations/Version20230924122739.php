<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230924122739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE region_erm_department (region_erm_id INT NOT NULL, department_id INT NOT NULL, INDEX IDX_60B682D02971434C (region_erm_id), INDEX IDX_60B682D0AE80F5DF (department_id), PRIMARY KEY(region_erm_id, department_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE region_erm_department ADD CONSTRAINT FK_60B682D02971434C FOREIGN KEY (region_erm_id) REFERENCES region_erm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE region_erm_department ADD CONSTRAINT FK_60B682D0AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE region_erm_department DROP FOREIGN KEY FK_60B682D02971434C');
        $this->addSql('ALTER TABLE region_erm_department DROP FOREIGN KEY FK_60B682D0AE80F5DF');
        $this->addSql('DROP TABLE region_erm_department');
    }
}
