<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180123080702 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function up(Schema $schema)
    {
        $encoder = $this->container->get('security.password_encoder');
        $user = new User();
        $password = $encoder->encodePassword($user, 'admin');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO imp_user (username, password, email, is_active) VALUES (\'admin\', \''. $password .'\', \'admin@example.com\', 1)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
