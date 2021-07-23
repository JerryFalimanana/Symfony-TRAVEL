<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210723102229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `add` ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE `add` ADD CONSTRAINT FK_FD1A73E7F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FD1A73E7F675F31B ON `add` (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `add` DROP FOREIGN KEY FK_FD1A73E7F675F31B');
        $this->addSql('DROP INDEX IDX_FD1A73E7F675F31B ON `add`');
        $this->addSql('ALTER TABLE `add` DROP author_id');
    }
}
