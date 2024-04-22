<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422063300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inquiry ADD receiver_id INT NOT NULL, DROP email');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F0CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A3903F0CD53EDB6 ON inquiry (receiver_id)');
        $this->addSql('ALTER TABLE inquiry RENAME INDEX fk_5a3903f0f624b39d TO IDX_5A3903F0F624B39D');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inquiry DROP FOREIGN KEY FK_5A3903F0CD53EDB6');
        $this->addSql('DROP INDEX IDX_5A3903F0CD53EDB6 ON inquiry');
        $this->addSql('ALTER TABLE inquiry ADD email VARCHAR(180) NOT NULL, DROP receiver_id');
        $this->addSql('ALTER TABLE inquiry RENAME INDEX idx_5a3903f0f624b39d TO FK_5A3903F0F624B39D');
    }
}
