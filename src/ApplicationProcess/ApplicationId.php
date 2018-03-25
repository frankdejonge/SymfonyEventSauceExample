<?php

namespace AcmeCompany\ApplicationProcess;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;

class ApplicationId implements AggregateRootId
{
    /**
     * @var string
     */
    private $identifier;

    private function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public static function create(): ApplicationId
    {
        return new static(Uuid::uuid4()->toString());
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $aggregateRootId
     *
     * @return static
     */
    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static($aggregateRootId);
    }
}