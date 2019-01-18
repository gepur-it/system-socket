<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 18.01.19
 */

namespace GepurIt\SystemSocketBundle;

use GepurIt\SystemSocketBundle\Exception\SystemSocketException;
use GepurIt\SystemSocketBundle\Rabbit\StreamQueue;

/**
 * Class SystemSocket
 * @package GepurIt\SystemSocketBundle
 */
class SystemSocket
{
    /**
     * @var StreamQueue
     */
    private $queue;

    /**
     * SystemSocket constructor.
     *
     * @param StreamQueue $queue
     */
    public function __construct(StreamQueue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param SystemSocketRequest $request
     *
     * @throws SystemSocketException
     */
    public function stream(SystemSocketRequest $request)
    {
        $this->streamSerializable($request);
    }

    /**
     * @param \JsonSerializable $request
     *
     * @throws SystemSocketException
     */
    public function streamSerializable($request)
    {
        try {
            $exchange = $this->queue->getExchange();
            $exchange->publish(
                json_encode($request),
                $this->queue->getName()
            );
        } catch (\AMQPException $exception) {
            throw new SystemSocketException("Error send request to system socket", 0, $exception);
        }
    }
}
