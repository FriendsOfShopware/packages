<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200127172305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE version ADD autoload JSON NOT NULL COMMENT \'(DC2Type:json_array)\', ADD autoload_dev JSON NOT NULL COMMENT \'(DC2Type:json_array)\', CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE homepage homepage VARCHAR(255) DEFAULT NULL, CHANGE license license VARCHAR(255) DEFAULT NULL, CHANGE release_date release_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE package CHANGE store_link store_link VARCHAR(255) DEFAULT NULL, CHANGE release_date release_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE package CHANGE store_link store_link VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE release_date release_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE version DROP autoload, DROP autoload_dev, CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE homepage homepage VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE license license VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE release_date release_date DATETIME DEFAULT \'NULL\'');
    }
}
