<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191030201931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE producer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_976449DC5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE package ADD producer_id INT NOT NULL');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE68679589B658FE FOREIGN KEY (producer_id) REFERENCES producer (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DE6867955E237E06 ON package (name)');
        $this->addSql('CREATE INDEX IDX_DE68679589B658FE ON package (producer_id)');
        $this->addSql('ALTER TABLE version ADD homepage VARCHAR(255) DEFAULT NULL, ADD license VARCHAR(255) DEFAULT NULL, ADD authors JSON NOT NULL COMMENT \'(DC2Type:json_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE68679589B658FE');
        $this->addSql('DROP TABLE producer');
        $this->addSql('DROP INDEX UNIQ_DE6867955E237E06 ON package');
        $this->addSql('DROP INDEX IDX_DE68679589B658FE ON package');
        $this->addSql('ALTER TABLE package DROP producer_id');
        $this->addSql('ALTER TABLE version DROP homepage, DROP license, DROP authors');
    }
}
