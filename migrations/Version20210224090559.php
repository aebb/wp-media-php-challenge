<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224090559 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_item DROP FOREIGN KEY FK_6CA8B1658DB60186');
        $this->addSql('ALTER TABLE task_item ADD CONSTRAINT FK_6CA8B1658DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_item DROP FOREIGN KEY FK_6CA8B1658DB60186');
        $this->addSql('ALTER TABLE task_item ADD CONSTRAINT FK_6CA8B1658DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }
}
