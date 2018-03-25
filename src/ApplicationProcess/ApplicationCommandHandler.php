<?php

namespace AcmeCompany\ApplicationProcess;

use App\PersonalInformation\PersonalInformationRepository;
use EventSauce\EventSourcing\AggregateRootRepository;
use function get_class;
use LogicException;
use function method_exists;
use Ramsey\Uuid\Uuid;
use function substr;

class ApplicationCommandHandler
{
    /**
     * @var AggregateRootRepository
     */
    private $repository;

    /**
     * @var PersonalInformationRepository
     */
    private $personalInformation;

    public function __construct(
        AggregateRootRepository $repository,
        PersonalInformationRepository $personalInformation
    ) {
        $this->repository = $repository;
        $this->personalInformation = $personalInformation;
    }

    public function handle(object $command)
    {
        /** @var StartApplication $command */
        /** @var Application $application */
        $application = $this->repository->retrieve($command->applicationId());

        try {
            $className = get_class($command);
            $commandName = substr($className, strrpos($className, '\\') + 1);

            if ( ! method_exists($this, 'handle'.$commandName)) {
                throw new LogicException(self::class . ' is missing a handler for ' . $commandName);
            }

            $this->{'handle'.$commandName}($application, $command);
        } finally {
            $this->repository->persist($application);
        }
    }

    protected function handleStartApplication(Application $application)
    {
        $application->start();
    }

    protected function handleSpecifyApplicationInformation(Application $application, SpecifyApplicationInformation $command)
    {
        $ref = Uuid::fromString($command->applicationId()->toString());

        $application->specifyApplicationInformation(
            $this->personalInformation,
            $ref,
            $command->firstName(),
            $command->lastName(),
            $command->email()
        );
    }

    protected function handleDeclineApplication(Application $application, DeclineApplication $command)
    {
        $application->decline($command->reason());
    }

    protected function handleApproveApplication(Application $application, ApproveApplication $command)
    {
        $application->approve();
    }
}