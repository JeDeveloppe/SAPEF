<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230921204548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop ADD department_id INT NOT NULL');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('CREATE INDEX IDX_AC6A4CA2AE80F5DF ON shop (department_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA2AE80F5DF');
        $this->addSql('DROP INDEX IDX_AC6A4CA2AE80F5DF ON shop');
        $this->addSql('ALTER TABLE shop DROP department_id');
    }
}
