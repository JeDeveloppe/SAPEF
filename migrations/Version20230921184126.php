<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230921184126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, name VARCHAR(255) NOT NULL, longitude NUMERIC(9, 6) NOT NULL, latitude NUMERIC(9, 6) NOT NULL, postal_code VARCHAR(8) NOT NULL, INDEX IDX_2D5B0234AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, code_map VARCHAR(255) NOT NULL, INDEX IDX_CD1DE18A98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE desk (id INT AUTO_INCREMENT NOT NULL, name_id INT NOT NULL, role_id INT NOT NULL, updated_by_id INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_56E246671179CD6 (name_id), INDEX IDX_56E2466D60322AC (role_id), INDEX IDX_56E2466896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE desk_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE elu (id INT AUTO_INCREMENT NOT NULL, name_id INT NOT NULL, status_id INT NOT NULL, region_erm_id INT NOT NULL, INDEX IDX_587A71C171179CD6 (name_id), INDEX IDX_587A71C16BF700BD (status_id), INDEX IDX_587A71C12971434C (region_erm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE elu_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mean_of_paiement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meeting (id INT AUTO_INCREMENT NOT NULL, name_id INT NOT NULL, place_id INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F515E13971179CD6 (name_id), INDEX IDX_F515E139DA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meeting_name (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meeting_place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, name_id INT NOT NULL, means_of_paiement_id INT NOT NULL, created_by_id INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B1DC7A1E71179CD6 (name_id), INDEX IDX_B1DC7A1EAE1A8563 (means_of_paiement_id), INDEX IDX_B1DC7A1EB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region_erm (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color_background VARCHAR(10) NOT NULL, color_text VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, counter_mark VARCHAR(10) NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_AC6A4CA28BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, job_id INT NOT NULL, shop_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nickname VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_visite_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', image VARCHAR(255) DEFAULT NULL, phone VARCHAR(18) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649BE04EA9 (job_id), INDEX IDX_8D93D6494D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A98260155 FOREIGN KEY (region_id) REFERENCES region_erm (id)');
        $this->addSql('ALTER TABLE desk ADD CONSTRAINT FK_56E246671179CD6 FOREIGN KEY (name_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE desk ADD CONSTRAINT FK_56E2466D60322AC FOREIGN KEY (role_id) REFERENCES desk_role (id)');
        $this->addSql('ALTER TABLE desk ADD CONSTRAINT FK_56E2466896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE elu ADD CONSTRAINT FK_587A71C171179CD6 FOREIGN KEY (name_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE elu ADD CONSTRAINT FK_587A71C16BF700BD FOREIGN KEY (status_id) REFERENCES elu_status (id)');
        $this->addSql('ALTER TABLE elu ADD CONSTRAINT FK_587A71C12971434C FOREIGN KEY (region_erm_id) REFERENCES region_erm (id)');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E13971179CD6 FOREIGN KEY (name_id) REFERENCES meeting_name (id)');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E139DA6A219 FOREIGN KEY (place_id) REFERENCES meeting_place (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E71179CD6 FOREIGN KEY (name_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EAE1A8563 FOREIGN KEY (means_of_paiement_id) REFERENCES mean_of_paiement (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EB03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA28BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6494D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234AE80F5DF');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A98260155');
        $this->addSql('ALTER TABLE desk DROP FOREIGN KEY FK_56E246671179CD6');
        $this->addSql('ALTER TABLE desk DROP FOREIGN KEY FK_56E2466D60322AC');
        $this->addSql('ALTER TABLE desk DROP FOREIGN KEY FK_56E2466896DBBDE');
        $this->addSql('ALTER TABLE elu DROP FOREIGN KEY FK_587A71C171179CD6');
        $this->addSql('ALTER TABLE elu DROP FOREIGN KEY FK_587A71C16BF700BD');
        $this->addSql('ALTER TABLE elu DROP FOREIGN KEY FK_587A71C12971434C');
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E13971179CD6');
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E139DA6A219');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E71179CD6');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EAE1A8563');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EB03A8386');
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA28BAC62AF');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649BE04EA9');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6494D16C4DD');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE desk');
        $this->addSql('DROP TABLE desk_role');
        $this->addSql('DROP TABLE elu');
        $this->addSql('DROP TABLE elu_status');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE mean_of_paiement');
        $this->addSql('DROP TABLE meeting');
        $this->addSql('DROP TABLE meeting_name');
        $this->addSql('DROP TABLE meeting_place');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE region_erm');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
