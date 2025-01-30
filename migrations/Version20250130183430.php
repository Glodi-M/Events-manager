<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130183430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration_event DROP FOREIGN KEY FK_B404AA4F71F7E88B');
        $this->addSql('ALTER TABLE registration_event DROP FOREIGN KEY FK_B404AA4F833D8F43');
        $this->addSql('ALTER TABLE registration_user DROP FOREIGN KEY FK_42ADC195833D8F43');
        $this->addSql('ALTER TABLE registration_user DROP FOREIGN KEY FK_42ADC195A76ED395');
        $this->addSql('DROP TABLE registration_event');
        $this->addSql('DROP TABLE registration_user');
        $this->addSql('ALTER TABLE registration ADD user_id INT NOT NULL, ADD event_id INT NOT NULL');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A7A76ED395 ON registration (user_id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A771F7E88B ON registration (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registration_event (registration_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_B404AA4F833D8F43 (registration_id), INDEX IDX_B404AA4F71F7E88B (event_id), PRIMARY KEY(registration_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE registration_user (registration_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_42ADC195833D8F43 (registration_id), INDEX IDX_42ADC195A76ED395 (user_id), PRIMARY KEY(registration_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE registration_event ADD CONSTRAINT FK_B404AA4F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_event ADD CONSTRAINT FK_B404AA4F833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_user ADD CONSTRAINT FK_42ADC195833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_user ADD CONSTRAINT FK_42ADC195A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7A76ED395');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A771F7E88B');
        $this->addSql('DROP INDEX IDX_62A8A7A7A76ED395 ON registration');
        $this->addSql('DROP INDEX IDX_62A8A7A771F7E88B ON registration');
        $this->addSql('ALTER TABLE registration DROP user_id, DROP event_id');
    }
}
