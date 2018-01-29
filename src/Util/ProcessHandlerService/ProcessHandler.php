<?php
declare(strict_types=1);
namespace App\Util\ProcessHandlerService;

use Symfony\Component\Process\Process;

class ProcessHandler
{
    /**
     * @param int $pid
     *
     * @return bool
     */
    public function killProcess(int $pid)
    {
        $process = new Process('kill ' . $pid);
        $process->run();

        return !$this->processStatus($pid);
    }

    /**
     * @param $pid
     *
     * @return bool
     */
    public function processStatus($pid)
    {
        if (is_null($pid)) {
            return false;
        }

        $process = new Process('ps -o time -p ' . $pid);
        $process->run();

        $output = explode(PHP_EOL, trim($process->getOutput()));

        return isset($output[1]);
    }
}