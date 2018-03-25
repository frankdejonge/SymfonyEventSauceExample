<?php

namespace AcmeCompany\ApplicationProcess\PendingApplications;

use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;

class DoctrinePendingApplicationRepository extends EntityRepository implements PendingApplicationRepository
{
    public function findForApplication(UuidInterface $id): PendingApplication
    {
        $application = $this->find($id);

        if ( ! $application instanceof PendingApplication) {
            throw new PendingApplicationNotFound();
        }

        return $application;
    }

    public function persist(PendingApplication $pendingApplication)
    {
        $em = $this->getEntityManager();
        $em->persist($pendingApplication);
        $em->flush();
    }
}