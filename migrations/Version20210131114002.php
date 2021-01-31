<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131114002 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dependency_package (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, composer_json JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dependency_package_version (dependency_package_id INT NOT NULL, version_id INT NOT NULL, INDEX IDX_A879D6750B1295D (dependency_package_id), INDEX IDX_A879D674BBC2705 (version_id), PRIMARY KEY(dependency_package_id, version_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dependency_package_version ADD CONSTRAINT FK_A879D6750B1295D FOREIGN KEY (dependency_package_id) REFERENCES dependency_package (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dependency_package_version ADD CONSTRAINT FK_A879D674BBC2705 FOREIGN KEY (version_id) REFERENCES version (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dependency_package_version DROP FOREIGN KEY FK_A879D6750B1295D');
        $this->addSql('DROP TABLE dependency_package');
        $this->addSql('DROP TABLE dependency_package_version');
    }
}
