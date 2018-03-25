<?php

namespace AcmeCompany\ApplicationProcess\PendingApplications;

use Ramsey\Uuid\UuidInterface;

class InMemoryPendingApplicationRepository implements PendingApplicationRepository
{
    private $applications = [];

    public function findForApplication(UuidInterface $id): PendingApplication
    {
        $application = $this->applications[$id->toString()] ?? null;

        if ( ! $application instanceof PendingApplication) {
            throw new PendingApplicationNotFound();
        }

        return $application;
    }

    public function persist(PendingApplication $pendingApplication)
    {
        $this->applications[$pendingApplication->id()->toString()] = $pendingApplication;
    }
}