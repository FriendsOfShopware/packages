<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200623175523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE version ADD composer_json JSON DEFAULT NULL, CHANGE extra extra JSON NOT NULL, CHANGE require_section require_section JSON NOT NULL, CHANGE authors authors JSON NOT NULL, CHANGE autoload autoload JSON NOT NULL, CHANGE autoload_dev autoload_dev JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE version DROP composer_json, CHANGE authors authors JSON CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin` COMMENT \'(DC2Type:json_array)\', CHANGE extra extra JSON CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin` COMMENT \'(DC2Type:json_array)\', CHANGE require_section require_section JSON CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin` COMMENT \'(DC2Type:json_array)\', CHANGE autoload autoload JSON CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin` COMMENT \'(DC2Type:json_array)\', CHANGE autoload_dev autoload_dev JSON CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin` COMMENT \'(DC2Type:json_array)\'');
    }
}
