<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180110090706 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE imp_sensor ADD state ENUM(\'active\', \'inactive\') NOT NULL COMMENT \'(DC2Type:sensor_state)\', CHANGE active fetchable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE imp_behavior ADD source_condition ENUM(\'is_off\', \'is_on\', \'equals\', \'not_equals\', \'bigger_than\', \'smaller_than\', \'bigger_equals_than\', \'smaller_equals_than\') NOT NULL COMMENT \'(DC2Type:sensor_conditions_enum)\', ADD source_argument INT DEFAULT NULL, ADD dependent_action ENUM(\'turn_off\', \'turn_on\', \'set\') NOT NULL COMMENT \'(DC2Type:sensor_actions_enum)\', DROP source_property, DROP predicate, DROP predicate_argument, DROP dependent_property, DROP action, CHANGE action_argument action_argument INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE imp_behavior ADD source_property VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD predicate VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD predicate_argument VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD dependent_property VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD action VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP source_condition, DROP source_argument, DROP dependent_action, CHANGE action_argument action_argument VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE imp_sensor DROP state, CHANGE fetchable active TINYINT(1) NOT NULL');
    }
}
