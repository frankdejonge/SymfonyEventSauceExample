<?php

namespace App\PersonalInformation;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="DoctrinePersonalInformationRepository")
 * @ORM\Table(name="personal_information")
 */
class PersonalInformation
{
    /**
     * @var UuidInterface
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $personalInformation;

    public function __construct(UuidInterface $id, array $personalInformation)
    {
        $this->id = $id;
        $this->personalInformation = $personalInformation;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function personalInformation(): array
    {
        return $this->personalInformation;
    }
}