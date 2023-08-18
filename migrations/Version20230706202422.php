<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706202422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discounts (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products ADD discounts_id INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A6A35CCB1 FOREIGN KEY (discounts_id) REFERENCES discounts (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A6A35CCB1 ON products (discounts_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A6A35CCB1');
        $this->addSql('DROP TABLE discounts');
        $this->addSql('DROP INDEX IDX_B3BA5A5A6A35CCB1 ON products');
        $this->addSql('ALTER TABLE products DROP discounts_id');
    }
}
