<?php declare(strict_types=1);

namespace DoctrineMigrations;

use DateTime;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\IrreversibleMigrationException;
use Doctrine\DBAL\Schema\Schema;
use PDO;
use function date_format;
use function json_encode;

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

        $additionalDataInterval = json_encode(['interval' => 5]);
        $date = new DateTime();

        $stringDate = date_format($date, 'Y-m-d H:i:s');

        $this->addSql(
            'INSERT INTO imperius.imp_job (name, command, immediate_rerun, created_at) VALUES (\'Background sensor server\', \'sensors:scan\', 1, ?)',
            [
                $stringDate,
            ],
            [
                PDO::PARAM_STR,
                PDO::PARAM_STR,
            ]
        );
        $this->addSql(
            'INSERT INTO imperius.imp_job (name, command, immediate_rerun, created_at) VALUES (\'Background job scheduler\', \'sensors:scheduled\', 1, ?)',
            [
                $stringDate,
            ],
            [
                PDO::PARAM_STR,
                PDO::PARAM_STR,
            ]
        );
        $this->addSql(
            'INSERT INTO imperius.imp_job (name, command, immediate_rerun, created_at, additional_data) VALUES (\'Background sensor refresh\', \'sensors:refresh\', 1, ?, ?)',
            [
                $stringDate,
                $additionalDataInterval,
            ],
            [
                PDO::PARAM_STR,
                PDO::PARAM_STR,
            ]
        );
        $this->addSql(
            'INSERT INTO imperius.imp_job (name, command, immediate_rerun, created_at, additional_data) VALUES (\'Background job status\', \'jobs:refresh\', 1, ?, ?)',
            [
                $stringDate,
                $additionalDataInterval,
            ],
            [
                PDO::PARAM_STR,
                PDO::PARAM_STR,
            ]
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
