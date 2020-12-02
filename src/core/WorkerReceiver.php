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

    /**
     * Process incoming request to generate pdf invoices and send them through
     * email.
     */
    public function listen()
    {
        $this->listen->listen();
    }

    /**
     * process received request
     *
     * @param AMQPMessage $msg
     */
    public function process(AMQPMessage $msg): void
    {
        $this->process->process($msg);
    }

    /**
     * Generates invoice's pdf
     *
     * @return WorkerReceiver
     */
    protected function generatePdf(): WorkerReceiver
    {
        $this->log->info('Generating PDF...');

        /**
         * Mocking a pdf generation processing time.  This will take between
         * 2 and 5 seconds
         */
        sleep(mt_rand(2, 5));

        $this->log->info('...PDF generated');

        return $this;
    }

    /**
     * Sends email
     */
    protected function sendEmail(): void
    {
        $this->log->info('Sending email...');

        /**
         * Mocking email sending time.  This will take between 1 and 3 seconds
         */
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