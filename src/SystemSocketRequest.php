<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 23.11.18
 * Time: 11:34
 */

namespace GepurIt\SystemSocketBundle;

/**
 * Class SystemSocketRequest
 * @package CrmBundle\SystemSocket
 */
class SystemSocketRequest implements \JsonSerializable
{
    const MODULE__HEADER = 'header';
    const MODULE__LOCAL_STORAGE = 'ls';

    const MODULE__HEADER_ACTION_APPEAL = 'appeal';
    const MODULE__LOCAL_STORAGE_ACTION__UPDATE_CANNED_PHRASE = 'updateCannedPhrase';

    const MODULE__SIDEBAR = 'sidebar';

    const TYPE__BROADCAST = 'broadcast';
    const TYPE__SUBSCRIBE = 'subscribe';


    /** @var string */
    private $module;

    /** @var string */
    private $action;

    /** @var string */
    private $type;

    /** @var array */
    private $recipients = [];

    private $payload = [];

    /**
     * SystemSocketRequest constructor.
     * @param string $module
     * @param string $action
     * @param string $type
     */
    public function __construct(string $module, string $action, string $type)
    {
        $this->module = $module;
        $this->action = $action;
        $this->type = $type;
    }

    /**
     * @param string $recipientId
     */
    public function addRecipientId(string $recipientId): void
    {
        $this->recipients[] = $recipientId;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addPayload(string $key, string $value): void
    {
        $this->payload[$key] = $value;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'module' => $this->module,
            'action' => $this->action,
            'type' => $this->type,
            'recipients' => $this->recipients,
            'payload' => $this->payload,
        ];
    }
}
