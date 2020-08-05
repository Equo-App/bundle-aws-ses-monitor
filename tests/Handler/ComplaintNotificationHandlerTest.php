<?php

/*
 * This file is part of the Serendipity HQ Aws Ses Bundle.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Bundle\AwsSesMonitorBundle\Tests\Handler;

use PHPUnit\Framework\TestCase;
use SerendipityHQ\Bundle\AwsSesMonitorBundle\Entity\EmailStatus;
use SerendipityHQ\Bundle\AwsSesMonitorBundle\Entity\MailMessage;
use SerendipityHQ\Bundle\AwsSesMonitorBundle\Handler\ComplaintNotificationHandler;
use SerendipityHQ\Bundle\AwsSesMonitorBundle\Manager\EmailStatusManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * {@inheritdoc}
 */
final class ComplaintNotificationHandlerTest extends TestCase
{
    /**
     * @var string[][]|string[][][][]
     */
    private const TEST = [
        'complaint' => [
            'complainedRecipients' => [
                [
                    'emailAddress'   => 'test_recipient@example.com',
                    'status'         => 'status',
                    'diagnosticCode' => 'diagnostic code',
                    'action'         => 'the action to take',
                ],
            ],
            'timestamp'             => '2016-08-01 00:00:00',
            'userAgent'             => 'the user agent',
            'complaintFeedbackType' => 'complaint feedback type',
            'feedbackId'            => 'the id of the feedback',
            'arrivalDate'           => '2016-08-01 00:00:00',
        ],
    ];

    public function testProcessNotification(): void
    {
        $mockEmailStatus  = $this->createMock(EmailStatus::class);
        $mockMailMessage  = $this->createMock(MailMessage::class);
        $mockEmailManager = $this->createMock(EmailStatusManager::class);
        $mockEmailManager->method('loadOrCreateEmailStatus')->willReturn($mockEmailStatus);
        $resource = new ComplaintNotificationHandler($mockEmailManager);
        $response = $resource->processNotification(self::TEST, $mockMailMessage);
        self::assertInstanceOf(Response::class, $response);
    }
}
