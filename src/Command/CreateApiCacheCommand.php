<?php
declare(strict_types=1);
namespace App\Command;

use App\Command\Repository\SensorApiRedisRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateApiCacheCommand extends ContainerAwareCommand
{
    /** @var SensorApiRedisRepository */
    private $sensorApiRedisRepository;

    protected function configure()
    {
        $this->setName('sensors:create:api-cache');
    }

    public function __construct(
        $name = null,
        SensorApiRedisRepository $sensorApiRedisRepository
    ) {
        parent::__construct($name);
        $this->sensorApiRedisRepository = $sensorApiRedisRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating API cache for sensors.');
        $this->sensorApiRedisRepository->generateCacheFromDatabase();
    }
}
