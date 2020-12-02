<?php


namespace AYakovlev\core\Receiver;


use AYakovlev\core\WorkerReceiver;
use PhpAmqpLib\Message\AMQPMessage;

class Process extends WorkerReceiver
{
    private WorkerReceiver $workerReceiver;

    public function __construct(WorkerReceiver $workerReceiver)
    {
        parent::__construct();
        $this->workerReceiver = $workerReceiver;
    }

    /**
     * process received request
     *
     * @param AMQPMessage $msg
     */
    public function process(AMQPMessage $msg): void
    {
        $this->workerReceiver->getLog()->info('Received message: ' . $msg->body);

        $this->workerReceiver->sendEmail();

        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }
}