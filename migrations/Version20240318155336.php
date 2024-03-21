<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318155336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE countrys (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F813B025C87');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F814887F3F8');
        $this->addSql('ALTER TABLE billing_country DROP FOREIGN KEY FK_385D42B13B025C87');
        $this->addSql('ALTER TABLE billing_country DROP FOREIGN KEY FK_385D42B1F92F3E70');
        $this->addSql('ALTER TABLE shipping_country DROP FOREIGN KEY FK_B53BCFA24887F3F8');
        $this->addSql('ALTER TABLE shipping_country DROP FOREIGN KEY FK_B53BCFA2F92F3E70');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE billing');
        $this->addSql('DROP TABLE billing_country');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE shipping_country');
        $this->addSql('ALTER TABLE clients ADD country_id INT NOT NULL, ADD address2_id INT DEFAULT NULL, ADD address1 VARCHAR(255) NOT NULL, ADD complement_adr1 VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) NOT NULL, ADD code_postal VARCHAR(255) NOT NULL, ADD phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E74F92F3E70 FOREIGN KEY (country_id) REFERENCES countrys (id)');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E74E443B061 FOREIGN KEY (address2_id) REFERENCES shipping (id)');
        $this->addSql('CREATE INDEX IDX_C82E74F92F3E70 ON clients (country_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C82E74E443B061 ON clients (address2_id)');
        $this->addSql('ALTER TABLE shipping ADD country_id INT DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD code_postal VARCHAR(255) DEFAULT NULL, DROP zip_code, DROP adresse, CHANGE complement_adr complement_adr VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C1724F92F3E70 FOREIGN KEY (country_id) REFERENCES countrys (id)');
        $this->addSql('CREATE INDEX IDX_2D1C1724F92F3E70 ON shipping (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E74F92F3E70');
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C1724F92F3E70');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, billing_id INT NOT NULL, shipping_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_D4E6F813B025C87 (billing_id), UNIQUE INDEX UNIQ_D4E6F814887F3F8 (shipping_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE billing (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, city VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, zip_code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, complement_adr VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE billing_country (billing_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_385D42B13B025C87 (billing_id), INDEX IDX_385D42B1F92F3E70 (country_id), PRIMARY KEY(billing_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE shipping_country (shipping_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_B53BCFA24887F3F8 (shipping_id), INDEX IDX_B53BCFA2F92F3E70 (country_id), PRIMARY KEY(shipping_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F813B025C87 FOREIGN KEY (billing_id) REFERENCES billing (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F814887F3F8 FOREIGN KEY (shipping_id) REFERENCES shipping (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE billing_country ADD CONSTRAINT FK_385D42B13B025C87 FOREIGN KEY (billing_id) REFERENCES billing (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE billing_country ADD CONSTRAINT FK_385D42B1F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shipping_country ADD CONSTRAINT FK_B53BCFA24887F3F8 FOREIGN KEY (shipping_id) REFERENCES shipping (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shipping_country ADD CONSTRAINT FK_B53BCFA2F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE countrys');
        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E74E443B061');
        $this->addSql('DROP INDEX IDX_C82E74F92F3E70 ON clients');
        $this->addSql('DROP INDEX UNIQ_C82E74E443B061 ON clients');
        $this->addSql('ALTER TABLE clients DROP country_id, DROP address2_id, DROP address1, DROP complement_adr1, DROP city, DROP code_postal, DROP phone');
        $this->addSql('DROP INDEX IDX_2D1C1724F92F3E70 ON shipping');
        $this->addSql('ALTER TABLE shipping ADD zip_code VARCHAR(255) DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL, DROP country_id, DROP address, DROP code_postal, CHANGE complement_adr complement_adr VARCHAR(255) NOT NULL');
    }
}
