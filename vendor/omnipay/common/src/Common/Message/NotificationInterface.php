<?php

namespace Omnipay\Common\Message;

/**
 * Incoming notification
 */
interface NotificationInterface extends MessageInterface
{
    const STATUS_COMPLETED = 'completed';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';

    /**
     * Gateway Reference
     *
     * @return string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference();

    /**
     * Was the transaction successful?
     *
     * @return string Transaction status, one of {@see STATUS_COMPLETED}, {@see #STATUS_PENDING},
     * or {@see #STATUS_FAILED}.
     */
    public function getTransactionStatus();

    /**
     * Response Message
     *
     * @return string A response message from the payment gateway
     */
    public function getMessage();
}
