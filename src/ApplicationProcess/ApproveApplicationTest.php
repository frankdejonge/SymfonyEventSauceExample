<?php

namespace AcmeCompany\ApplicationProcess;

class ApproveApplicationTest extends ApplicationTestCase
{
    /**
     * @before
     */
    public function preconditions()
    {
        $this->given(
            new ApplicationWasStarted(),
            new ApplicationInformationWasSpecified()
        );
    }

    /**
     * @test
     */
    public function approving_an_application()
    {
        $this->when(new ApproveApplication($this->aggregateRootId))
            ->then(new ApplicationWasApproved());
    }

    /**
     * @test
     */
    public function approving_an_application_that_was_declined()
    {
        $this->given(new ApplicationWasDeclined('Because reasons.'))
            ->when(new ApproveApplication($this->aggregateRootId))
            ->expectToFail(new ApplicationWasAlreadyDeclined());
    }
}