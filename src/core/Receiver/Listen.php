<?php


namespace AYakovlev\core\Receiver;


use AYakovlev\core\WorkerReceiver;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Listen
{
    private WorkerReceiver $workerReceiver;

    public function __construct(WorkerReceiver $workerReceiver)
    {
        $this->workerReceiver = $workerReceiver;
    }

    public function listen()
    {
        $this->workerReceiver->getLog()->info('Begin listen routine');

        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare(
            'invoice_queue',
            false,
            true, // постоянная очередь
            false,
            false
        );

        $channel->basic_qos(
            null,
            1,
            null
        );

        $channel->basic_consume(
            'invoice_queue',
            '',
            false,
            false,
            false,
            false,
            [$this->workerReceiver, 'process']
        );

        $this->workerReceiver->getLog()->info('Consuming from queue');

        while (count($channel->callbacks)) {
            $this->workerReceiver->getLog()->info('Waiting for incoming messages');
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}