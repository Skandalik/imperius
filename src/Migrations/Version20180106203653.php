<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180106203653 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE imp_behavior (id INT AUTO_INCREMENT NOT NULL, source_sensor_id INT NOT NULL, dependent_sensor_id INT NOT NULL, property ENUM(\'active\', \'status\'), behavior_condition VARCHAR(255) NOT NULL, dependent_sensor_property ENUM(\'active\', \'status\'), behavior_action VARCHAR(255) NOT NULL, INDEX IDX_50453F2FCAD90AD1 (source_sensor_id), INDEX IDX_50453F2FD658931E (dependent_sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE imp_behavior ADD CONSTRAINT FK_50453F2FCAD90AD1 FOREIGN KEY (source_sensor_id) REFERENCES imp_sensor (id)');
        $this->addSql('ALTER TABLE imp_behavior ADD CONSTRAINT FK_50453F2FD658931E FOREIGN KEY (dependent_sensor_id) REFERENCES imp_sensor (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE imp_behavior');
    }
}
