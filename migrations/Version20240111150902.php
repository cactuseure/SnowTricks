<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240111150902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figure ADD cover_id INT NOT NULL, ADD youtube_videos JSON NOT NULL');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37A922726E9 FOREIGN KEY (cover_id) REFERENCES media_object (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F57B37A922726E9 ON figure (cover_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37A922726E9');
        $this->addSql('DROP INDEX UNIQ_2F57B37A922726E9 ON figure');
        $this->addSql('ALTER TABLE figure DROP cover_id, DROP youtube_videos');
    }
}
