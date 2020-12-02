<?php


namespace AYakovlev\core;


use AYakovlev\core\Receiver\Listen;
use AYakovlev\core\Receiver\Process;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PhpAmqpLib\Message\AMQPMessage;

class WorkerReceiver
{
    /**
     * @var Logger
     */
    private Logger $log;
    private Listen $listen;
    private Process $process;

    public function __construct()
    {
        $this->log = new Logger('workerReceive');
        $this->log->pushHandler(new StreamHandler(__DIR__ . '../../logs/workerReceive.log', Logger::INFO));
        $this->listen = new Listen($this);
        $this->process = new Process($this);
    }

    public function listen()
    {
        $this->listen->listen();
    }

    /**
     * @param AMQPMessage $msg
     */
    public function process(AMQPMessage $msg): void
    {
        $this->process->process($msg);
    }

    /**
     * @return WorkerReceiver
     */
    protected function generatePdf(): WorkerReceiver
    {
        $this->log->info('Generating PDF...');
        sleep(mt_rand(2, 5));
        $this->log->info('...PDF generated');
        return $this;
    }

    protected function sendEmail(): void
    {
        $this->log->info('Sending email...');
        sleep(mt_rand(1,3));
        $this->log->info('Email sent');
    }

    /**
     * @return Logger
     */
    public function getLog(): Logger
    {
        return $this->log;
    }
}