<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180112221358 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migraation can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE imp_manual_behavior (id INT AUTO_INCREMENT NOT NULL, sensor_id INT NOT NULL, action_sensor_id INT NOT NULL, requirement ENUM(\'is_off\', \'is_on\', \'equals\', \'not_equals\', \'bigger_than\', \'smaller_than\', \'bigger_equals_than\', \'smaller_equals_than\') NOT NULL COMMENT \'(DC2Type:sensor_conditions_enum)\', requirement_argument INT DEFAULT NULL, action ENUM(\'turn_off\', \'turn_on\', \'set\') NOT NULL COMMENT \'(DC2Type:sensor_actions_enum)\', action_argument INT DEFAULT NULL, INDEX IDX_175D8DDAA247991F (sensor_id), INDEX IDX_175D8DDA6418A02 (action_sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE imp_manual_behavior ADD CONSTRAINT FK_175D8DDAA247991F FOREIGN KEY (sensor_id) REFERENCES imp_sensor (id)');
        $this->addSql('ALTER TABLE imp_manual_behavior ADD CONSTRAINT FK_175D8DDA6418A02 FOREIGN KEY (action_sensor_id) REFERENCES imp_sensor (id)');
        $this->addSql('DROP TABLE imp_behavior');
        $this->addSql('ALTER TABLE imp_scheduled_behavior CHANGE scheduled_action action ENUM(\'turn_off\', \'turn_on\', \'set\') NOT NULL COMMENT \'(DC2Type:sensor_actions_enum)\', CHANGE scheduled_action_argument action_argument INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE imp_behavior (id INT AUTO_INCREMENT NOT NULL, source_sensor_id INT NOT NULL, dependent_sensor_id INT NOT NULL, action_argument INT DEFAULT NULL, source_condition ENUM(\'is_off\', \'is_on\', \'equals\', \'not_equals\', \'bigger_than\', \'smaller_than\', \'bigger_equals_than\', \'smaller_equals_than\') NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:sensor_conditions_enum)\', source_argument INT DEFAULT NULL, dependent_action ENUM(\'turn_off\', \'turn_on\', \'set\') NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:sensor_actions_enum)\', INDEX IDX_50453F2FCAD90AD1 (source_sensor_id), INDEX IDX_50453F2FD658931E (dependent_sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE imp_behavior ADD CONSTRAINT FK_50453F2FCAD90AD1 FOREIGN KEY (source_sensor_id) REFERENCES imp_sensor (id)');
        $this->addSql('ALTER TABLE imp_behavior ADD CONSTRAINT FK_50453F2FD658931E FOREIGN KEY (dependent_sensor_id) REFERENCES imp_sensor (id)');
        $this->addSql('DROP TABLE imp_manual_behavior');
        $this->addSql('ALTER TABLE imp_scheduled_behavior CHANGE action scheduled_action ENUM(\'turn_off\', \'turn_on\', \'set\') NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:sensor_actions_enum)\', CHANGE action_argument scheduled_action_argument INT DEFAULT NULL');
    }
}
