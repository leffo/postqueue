<?php
namespace AYakovlev\core;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class WorkerSender
{
     /**
     * @param int $invoiceNum - номер накладной
     * @throws Exception
     */
    public function execute(int $invoiceNum): void
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare(
            'invoice_queue',
            false,
            true,       // Очередь сохраняется
            false,
            false
        );

        $msg = new AMQPMessage(
            $invoiceNum,
            array('delivery_mode' => 2)  // сообщение постоянное
        );

        $channel->basic_publish(
            $msg,
            '',
            'invoice_queue'
        );

        $channel->close();
        $connection->close();
    }
}
