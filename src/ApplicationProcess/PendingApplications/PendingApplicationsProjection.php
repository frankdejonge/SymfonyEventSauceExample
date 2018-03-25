<?php

namespace AcmeCompany\ApplicationProcess\PendingApplications;

use AcmeCompany\ApplicationProcess\ApplicationInformationWasSpecified;
use AcmeCompany\ApplicationProcess\ApplicationWasApproved;
use AcmeCompany\ApplicationProcess\ApplicationWasDeclined;
use App\PersonalInformation\PersonalInformationNotFound;
use App\PersonalInformation\PersonalInformationRepository;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use Ramsey\Uuid\Uuid;

class PendingApplicationsProjection implements Consumer
{
    /**
     * @var PendingApplicationRepository
     */
    private $repository;

    /**
     * @var PersonalInformationRepository
     */
    private $personalInformation;

    public function __construct(
        PendingApplicationRepository $repository,
        PersonalInformationRepository $personalInformation
    ) {
        $this->repository = $repository;
        $this->personalInformation = $personalInformation;
    }

    public function handle(Message $message)
    {
        $event = $message->event();

        if ($event instanceof ApplicationInformationWasSpecified) {
            $this->handleApplicationInformationWasSpecified($message, $event);
        } elseif ($event instanceof ApplicationWasApproved) {
            $application = $this->repository->findForApplication(Uuid::fromString($message->aggregateRootId()->toString()));
            $application->markApproved();
            $this->repository->persist($application);
        } elseif ($event instanceof ApplicationWasDeclined) {
            $application = $this->repository->findForApplication(Uuid::fromString($message->aggregateRootId()->toString()));
            $application->markDeclined();
            $this->repository->persist($application);
        }
    }

    protected function handleApplicationInformationWasSpecified(Message $message, ApplicationInformationWasSpecified $event): void
    {
        try {
            $personalInformation = $this->personalInformation
                ->retrieve(Uuid::fromString($message->aggregateRootId()->toString()))
                ->personalInformation();
        } catch (PersonalInformationNotFound $exception) {
            return; // Not found due to GDPR "forget me" request, safe to ignore.
        }

        $this->repository->persist(new PendingApplication(
            Uuid::fromString($message->aggregateRootId()->toString()),
            $personalInformation['firstName'],
            $personalInformation['lastName'],
            $personalInformation['email']
        ));
    }
}