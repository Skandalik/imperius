<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\IrreversibleMigrationException;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180128135008 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );
        $this->addSql(
            'INSERT INTO imperius.imp_job (name, command, immediate_rerun) VALUES (\'Background sensor server\', \'sensors:scan\', 1)'
        );
        $this->addSql(
            'INSERT INTO imperius.imp_job (name, command, immediate_rerun) VALUES (\'Background job scheduler\', \'sensors:scheduled\', 1)'
        );
        $this->addSql(
            'INSERT INTO imperius.imp_job (name, command, immediate_rerun) VALUES (\'Background sensor refresh\', \'sensors:refresh\', 1)'
        );
        $this->addSql(
            'INSERT INTO imperius.imp_job (name, command, immediate_rerun) VALUES (\'Background job status\', \'jobs:refresh\', 1)'
        );
    }

    /**
     * @param Schema $schema
     *
     * @throws IrreversibleMigrationException
     */
    public function down(Schema $schema)
    {
        throw new IrreversibleMigrationException('It\'s adding jobs to table so user can turn them on!');
    }
}
