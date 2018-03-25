<?php

namespace AcmeCompany\ApplicationProcess;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class ApplicationWasStarted implements SerializableEvent
{
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new ApplicationWasStarted();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): ApplicationWasStarted
    {
        return new ApplicationWasStarted();
    }
}

final class ApplicationInformationWasSpecified implements SerializableEvent
{
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new ApplicationInformationWasSpecified();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): ApplicationInformationWasSpecified
    {
        return new ApplicationInformationWasSpecified();
    }
}

final class ApplicationWasApproved implements SerializableEvent
{
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new ApplicationWasApproved();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): ApplicationWasApproved
    {
        return new ApplicationWasApproved();
    }
}

final class ApplicationWasDeclined implements SerializableEvent
{
    /**
     * @var string
     */
    private $reason;

    public function __construct(
        string $reason
    ) {
        $this->reason = $reason;
    }

    public function reason(): string
    {
        return $this->reason;
    }
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new ApplicationWasDeclined(
            (string) $payload['reason']);
    }

    public function toPayload(): array
    {
        return [
            'reason' => (string) $this->reason,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withReason(string $reason): ApplicationWasDeclined
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): ApplicationWasDeclined
    {
        return new ApplicationWasDeclined(
            (string) 'person is not qualified'
        );
    }
}

final class StartApplication
{
    /**
     * @var ApplicationId
     */
    private $applicationId;

    public function __construct(
        ApplicationId $applicationId
    ) {
        $this->applicationId = $applicationId;
    }

    public function applicationId(): ApplicationId
    {
        return $this->applicationId;
    }
}

final class SpecifyApplicationInformation
{
    /**
     * @var ApplicationId
     */
    private $applicationId;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    public function __construct(
        ApplicationId $applicationId,
        string $firstName,
        string $lastName,
        string $email
    ) {
        $this->applicationId = $applicationId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function applicationId(): ApplicationId
    {
        return $this->applicationId;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function email(): string
    {
        return $this->email;
    }
}

final class DeclineApplication
{
    /**
     * @var ApplicationId
     */
    private $applicationId;

    /**
     * @var string
     */
    private $reason;

    public function __construct(
        ApplicationId $applicationId,
        string $reason
    ) {
        $this->applicationId = $applicationId;
        $this->reason = $reason;
    }

    public function applicationId(): ApplicationId
    {
        return $this->applicationId;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}

final class ApproveApplication
{
    /**
     * @var ApplicationId
     */
    private $applicationId;

    public function __construct(
        ApplicationId $applicationId
    ) {
        $this->applicationId = $applicationId;
    }

    public function applicationId(): ApplicationId
    {
        return $this->applicationId;
    }
}
