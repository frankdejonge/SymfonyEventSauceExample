<?php

namespace AcmeCompany\ApplicationProcess\PendingApplications;

use Ramsey\Uuid\UuidInterface;

interface PendingApplicationRepository
{
    public function findForApplication(UuidInterface $id): PendingApplication;
    public function persist(PendingApplication $pendingApplication);
}