<?php

namespace AcmeCompany\ApplicationProcess;

use App\PersonalInformation\InMemoryPersonalInformationRepository;
use App\PersonalInformation\PersonalInformationRepository;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;

abstract class ApplicationTestCase extends AggregateRootTestCase
{
    /**
     * @var PersonalInformationRepository
     */
    private $personalInformation;

    protected function newAggregateRootId(): AggregateRootId
    {
        return ApplicationId::create();
    }

    protected function aggregateRootClassName(): string
    {
        return Application::class;
    }

    protected function handle($command)
    {
        $this->personalInformation = new InMemoryPersonalInformationRepository();
        $commandHandler = new ApplicationCommandHandler($this->repository, $this->personalInformation);
        $commandHandler->handle($command);
    }
}