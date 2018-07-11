<?php

/*
 * This file is part of the SHQAwsSesBundle.
 *
 * Copyright Adamo Aerendir Crespi 2015 - 2017.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Adamo Aerendir Crespi <hello@aerendir.me>
 * @copyright Copyright (C) 2015 - 2017 Aerendir. All rights reserved.
 * @license   MIT License.
 */

namespace SerendipityHQ\Bundle\AwsSesMonitorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A MailMessage Entity.
 *
 * This is called MailObject by Amazon.
 *
 * @see http://docs.aws.amazon.com/ses/latest/DeveloperGuide/notification-contents.html#mail-object
 *
 * @author Adamo Aerendir Crespi <hello@aerendir.me>
 *
 * @ORM\Table(name="shq_aws_ses_monitor_messages")
 * @ORM\Entity()
 */
class MailMessage
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\Id()
     */
    private $id;

    /**
     * A unique ID that Amazon SES assigned to the message.
     *
     * Amazon SES returned this value to you when you sent the message.
     * This message ID was assigned by Amazon SES. You can find the message ID of the original
     * email in the headers and commonHeaders fields of the mail object.
     *
     * @var string
     * @ORM\Column(name="message_id", type="string")
     */
    private $messageId;

    /**
     * The time at which the original message was sent (in ISO8601 format).
     *
     * Formerly "timestamp".
     *
     * @var \DateTime
     * @ORM\Column(name="sent_on", type="datetime")
     */
    private $sentOn;

    /**
     * The email address from which the original message was sent (the envelope MAIL FROM address).
     *
     * Formerly "source".
     *
     * @var string
     * @ORM\Column(name="sent_from", type="string")
     */
    private $sentFrom;

    /**
     * The Amazon Resource Name (ARN) of the identity that was used to send the email.
     *
     * In the case of sending authorization, the sourceArn is the ARN of the identity that the identity owner authorized
     * the delegate sender to use to send the email. For more information about sending authorization, see Using Sending
     * Authorization.
     *
     * @var string
     * @ORM\Column(name="source_arn", type="string")
     */
    private $sourceArn;

    /**
     * The AWS account ID of the account that was used to send the email.
     *
     * In the case of sending authorization, the sendingAccountId is the delegate sender's account ID.
     *
     * @var string
     * @ORM\Column(name="sending_account_id", type="string")
     */
    private $sendingAccountId;

    /**
     * A list of the email's original headers. Each header in the list has a name field and a value field.
     *
     * Any message ID within the headers field is from the original message that you passed to Amazon SES. The message
     * ID that Amazon SES subsequently assigned to the message is in the messageId field of the mail object.
     *
     * (Only present if the notification settings include the original email headers.)
     *
     * @var string|null
     * @ORM\Column(name="headers", type="text", nullable=true)
     */
    private $headers;

    /**
     * A list of the email's original, commonly used headers.
     *
     * Each header in the list has a name field and a value field.
     * Any message ID within the commonHeaders field is from the original message that you passed to Amazon SES. The
     * message ID that Amazon SES subsequently assigned to the message is in the messageId field of the mail object.
     *
     * (Only present if the notification settings include the original email headers.)
     *
     * @var string|null
     * @ORM\Column(name="common_headers", type="text", nullable=true)
     */
    private $commonHeaders;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="SerendipityHQ\Bundle\AwsSesMonitorBundle\Entity\Bounce", mappedBy="mailMessage")
     */
    private $bounces;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="SerendipityHQ\Bundle\AwsSesMonitorBundle\Entity\Complaint", mappedBy="mailMessage")
     */
    private $complaints;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="SerendipityHQ\Bundle\AwsSesMonitorBundle\Entity\Delivery", mappedBy="mailMessage")
     */
    private $deliveries;

    /**
     * MailMessage constructor.
     */
    public function __construct()
    {
        $this->bounces      = new ArrayCollection();
        $this->complaints   = new ArrayCollection();
        $this->deliveries   = new ArrayCollection();
    }

    /**
     * @param Bounce $bounce
     *
     * @return MailMessage
     */
    public function addBounce(Bounce $bounce): MailMessage
    {
        $this->bounces->add($bounce);

        if ($bounce->getMailMessage() !== $this) {
            $bounce->setMailMessage($this);
        }

        return $this;
    }

    /**
     * @param Complaint $complaint
     *
     * @return MailMessage
     */
    public function addComplaint(Complaint $complaint): MailMessage
    {
        $this->complaints->add($complaint);

        if ($complaint->getMailMessage() !== $this) {
            $complaint->setMailMessage($this);
        }

        return $this;
    }

    /**
     * @param Delivery $delivery
     *
     * @return MailMessage
     */
    public function addDelivery(Delivery $delivery): MailMessage
    {
        $this->deliveries->add($delivery);

        if ($delivery->getMailMessage() !== $this) {
            $delivery->setMailMessage($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getBounces(): Collection
    {
        return $this->bounces;
    }

    /**
     * @return Collection
     */
    public function getComplaints(): Collection
    {
        return $this->complaints;
    }

    /**
     * @return Collection
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * @return \DateTime
     */
    public function getSentOn(): \DateTime
    {
        return $this->sentOn;
    }

    /**
     * @return string
     */
    public function getSentFrom(): string
    {
        return $this->sentFrom;
    }

    /**
     * @return string
     */
    public function getSourceArn(): string
    {
        return $this->sourceArn;
    }

    /**
     * @return string
     */
    public function getSendingAccountId(): string
    {
        return $this->sendingAccountId;
    }

    /**
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getCommonHeaders(): string
    {
        return $this->commonHeaders;
    }

    /**
     * @param string $messageId
     *
     * @return MailMessage
     */
    public function setMessageId(string $messageId): MailMessage
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * @param \DateTime $sentOn
     *
     * @return MailMessage
     */
    public function setSentOn(\DateTime $sentOn): MailMessage
    {
        $this->sentOn = $sentOn;

        return $this;
    }

    /**
     * @param string $sentFrom
     *
     * @return MailMessage
     */
    public function setSentFrom(string $sentFrom): MailMessage
    {
        $this->sentFrom = $sentFrom;

        return $this;
    }

    /**
     * @param string $sourceArn
     *
     * @return MailMessage
     */
    public function setSourceArn(string $sourceArn): MailMessage
    {
        $this->sourceArn = $sourceArn;

        return $this;
    }

    /**
     * @param string $sendingAccountId
     *
     * @return MailMessage
     */
    public function setSendingAccountId(string $sendingAccountId): MailMessage
    {
        $this->sendingAccountId = $sendingAccountId;

        return $this;
    }

    /**
     * @param string $headers
     *
     * @return MailMessage
     */
    public function setHeaders(string $headers): MailMessage
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param string $commonHeaders
     *
     * @return MailMessage
     */
    public function setCommonHeaders(string $commonHeaders): MailMessage
    {
        $this->commonHeaders = $commonHeaders;

        return $this;
    }
}
