<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230921185451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE region_erm ADD color VARCHAR(10) NOT NULL, ADD color_hover VARCHAR(10) NOT NULL, DROP color_background, DROP color_text');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE region_erm ADD color_background VARCHAR(10) NOT NULL, ADD color_text VARCHAR(10) NOT NULL, DROP color, DROP color_hover');
    }
}
