<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319124249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD82EA2E54 FOREIGN KEY (commande_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD82EA2E54 ON product (commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD82EA2E54');
        $this->addSql('DROP INDEX IDX_D34A04AD82EA2E54 ON product');
        $this->addSql('ALTER TABLE product DROP commande_id');
    }
}
