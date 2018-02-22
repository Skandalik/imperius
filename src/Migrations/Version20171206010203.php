<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171206010203 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE imp_room (id INT AUTO_INCREMENT NOT NULL, room VARCHAR(255) NOT NULL, floor INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imp_sensor (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, uuid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', value_type VARCHAR(255) NOT NULL, switchable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, sensor_ip VARCHAR(50) NOT NULL, last_data_sent_at DATETIME DEFAULT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_17DD9FE2D17F50A6 (uuid), UNIQUE INDEX UNIQ_17DD9FE2B89D4D62 (sensor_ip), INDEX IDX_17DD9FE254177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE imp_sensor ADD CONSTRAINT FK_17DD9FE254177093 FOREIGN KEY (room_id) REFERENCES imp_room (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE imp_sensor DROP FOREIGN KEY FK_17DD9FE254177093');
        $this->addSql('DROP TABLE imp_room');
        $this->addSql('DROP TABLE imp_sensor');
    }
}
