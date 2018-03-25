<?php

namespace App\PersonalInformation;

use Ramsey\Uuid\UuidInterface;

class InMemoryPersonalInformationRepository implements PersonalInformationRepository
{
    private $personalInformation = [];

    public function persist(PersonalInformation $personalInformation)
    {
        $this->personalInformation[$personalInformation->id()->toString()] = $personalInformation;
    }

    public function retrieve(UuidInterface $id): PersonalInformation
    {
        $personalInformation = $this->personalInformation[$id->toString()];

        if ( ! $personalInformation instanceof PersonalInformation) {
            throw new PersonalInformationNotFound();
        }

        return $personalInformation;
    }
}