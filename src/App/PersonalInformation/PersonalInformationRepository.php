<?php


namespace App\PersonalInformation;

use Ramsey\Uuid\UuidInterface;

interface PersonalInformationRepository
{
    public function persist(PersonalInformation $personalInformation);

    /**
     * @param UuidInterface $id
     * @return PersonalInformation
     * @throws PersonalInformationNotFound
     */
    public function retrieve(UuidInterface $id): PersonalInformation;
}