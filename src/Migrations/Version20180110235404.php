<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180110235404 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE imp_scheduled_behavior (id INT AUTO_INCREMENT NOT NULL, sensor_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, last_run_at DATETIME DEFAULT NULL, finished_run_at DATETIME DEFAULT NULL, next_run_at DATETIME DEFAULT NULL, relative_date LONGTEXT NOT NULL, time TIME DEFAULT NULL, INDEX IDX_701993A8A247991F (sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE imp_scheduled_behavior ADD CONSTRAINT FK_701993A8A247991F FOREIGN KEY (sensor_id) REFERENCES imp_sensor (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE imp_scheduled_behavior');
    }
}
