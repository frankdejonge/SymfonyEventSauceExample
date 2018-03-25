<?php

namespace AcmeCompany\ApplicationProcess;

use App\PersonalInformation\PersonalInformation;
use App\PersonalInformation\PersonalInformationRepository;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour\AggregateRootBehaviour;
use Ramsey\Uuid\UuidInterface;
use function compact;
use function preg_match;

class Application implements AggregateRoot
{
    use AggregateRootBehaviour, LooseEventApplication {
        LooseEventApplication::apply insteadof AggregateRootBehaviour;
    }

    /**
     * @var bool
     */
    private $declined = false;

    public function start()
    {
        $this->recordThat(new ApplicationWasStarted());
    }

    public function specifyApplicationInformation(
        PersonalInformationRepository $personalInformation,
        UuidInterface $personalReference,
        string $firstName,
        string $lastName,
        string $email
    ) {
        $this->guardAgainstIncompleteAccountInformation($firstName, $lastName, $email);
        $this->guardAgainstInvalidEmail($email);
        $personalInformation->persist(new PersonalInformation($personalReference, compact('firstName', 'lastName', 'email')));
        $this->recordThat(new ApplicationInformationWasSpecified());
    }

    private function guardAgainstIncompleteAccountInformation(string $firstName, string $lastName, string $email)
    {
        if (empty($firstName) || empty($lastName) || empty($email)) {
            throw new SorryApplicationInformationIsNotComplete();
        }
    }

    private function guardAgainstInvalidEmail(string $email)
    {
        if (preg_match('/.+@.+\..+/', $email) !== 1) {
            throw new SorryEmailIsInvalid();
        }
    }

    public function decline($reason)
    {
        $this->recordThat(new ApplicationWasDeclined($reason));
    }

    protected function applyApplicationWasDeclined(ApplicationWasDeclined $event)
    {
        $this->declined = true;
    }

    public function approve()
    {
        if ($this->declined === true) {
            throw new ApplicationWasAlreadyDeclined();
        }

        $this->recordThat(new ApplicationWasApproved());
    }
}