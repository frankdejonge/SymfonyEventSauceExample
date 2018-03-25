<?php

namespace AcmeCompany\ApplicationProcess;

class DeclineApplicationTest extends ApplicationTestCase
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
    public function declining_an_application()
    {
        $this->when(new DeclineApplication($this->aggregateRootId, 'Just because!'))
            ->then(new ApplicationWasDeclined('Just because!'));
    }
}