<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180107005258 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE imp_behavior ADD source_property ENUM(\'active\', \'status\'), ADD predicate VARCHAR(255) NOT NULL, ADD predicate_argument VARCHAR(255) NOT NULL, ADD dependent_property ENUM(\'active\', \'status\'), ADD action VARCHAR(255) NOT NULL, ADD action_argument VARCHAR(255) NOT NULL, DROP property, DROP behavior_condition, DROP dependent_sensor_property, DROP behavior_action');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE imp_behavior ADD property VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD behavior_condition VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD dependent_sensor_property VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD behavior_action VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP source_property, DROP predicate, DROP predicate_argument, DROP dependent_property, DROP action, DROP action_argument');
    }
}
