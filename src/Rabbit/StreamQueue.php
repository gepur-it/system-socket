<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 19.10.18
 * Time: 14:33
 */

namespace GepurIt\SystemSocketBundle\Rabbit;

use GepurIt\RabbitMqBundle\Rabbit;

/**
 * Class StreamQuery
 * @package CrmBundle\Rabbit
 */
class StreamQueue
{
    /**
     * @var Rabbit
     */
    private $rabbit;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $deferred;

    /**
     * Helper constructor.
     * @param Rabbit $rabbit
     * @param string $name
     * @param string $deferred
     */
    public function __construct(Rabbit $rabbit, string $name, string $deferred = null)
    {
        $this->rabbit = $rabbit;
        $this->name = $name;
        $this->deferred = $deferred;
    }

    /**
     * @return \AMQPExchange
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function getExchange(): \AMQPExchange
    {
        $channel = $this->rabbit->getChannel();

        if (null !== $this->deferred) {
            $deferredExchange = new \AMQPExchange($channel);
            $deferredExchange->setName($this->deferred);
            $deferredExchange->setType(AMQP_EX_TYPE_FANOUT);
            $deferredExchange->declareExchange();
            $deferredQueue = new \AMQPQueue($channel);
            $deferredQueue->setName($this->deferred);
            $deferredQueue->setArgument('x-dead-letter-exchange', $this->name);
            $deferredQueue->setArgument('x-message-ttl', 600000);
            $deferredQueue->declareQueue();
            $deferredQueue->bind($this->deferred, $this->name);
        }

        $exchange = new \AMQPExchange($channel);
        $exchange->setName($this->name);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();

        $queue = new \AMQPQueue($channel);
        $queue->setName($this->name);
        $queue->setFlags(AMQP_DURABLE);
        $queue->setArgument('x-dead-letter-exchange', $this->deferred);
        $queue->declareQueue();
        $queue->bind($this->name, $this->name);

        return $exchange;
    }

    /**
     * @return \AMQPQueue
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function getQueue(): \AMQPQueue
    {
        $queue = new \AMQPQueue($this->rabbit->getChannel());
        $queue->setName($this->name);
        $queue->setFlags(AMQP_DURABLE);
        $queue->setArgument('x-dead-letter-exchange', $this->deferred);
        $queue->declareQueue();

        return $queue;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
