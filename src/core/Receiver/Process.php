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

        /**
         * If a consumer dies without sending an acknowledgement the AMQP broker
         * will redeliver it to another consumer or, if none are available at the
         * time, the broker will wait until at least one consumer is registered
         * for the same queue before attempting redelivery
         */
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }
}