<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171213232854 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE imp_sensor ADD name VARCHAR(255) DEFAULT NULL, ADD multi_value TINYINT(1) DEFAULT NULL, ADD minimum_value INT DEFAULT NULL, ADD maximum_value INT DEFAULT NULL, ADD value INT NOT NULL, CHANGE uuid uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE value_type value_type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE imp_sensor DROP name, DROP multi_value, DROP minimum_value, DROP maximum_value, DROP value, CHANGE uuid uuid CHAR(36) DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE value_type value_type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
