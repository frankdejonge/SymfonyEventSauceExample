<?php

namespace AcmeCompany\ApplicationProcess;

class StartApplicationTest extends ApplicationTestCase
{
    /**
     * @test
     */
    public function starting_an_application()
    {
        $this->when(new StartApplication($this->aggregateRootId()))
            ->then(
                new ApplicationWasStarted()
            );
    }
}