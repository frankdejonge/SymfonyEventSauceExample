<?php

namespace AcmeCompany\ApplicationProcess;

use Ramsey\Uuid\Uuid;

class SpecifyApplicationInformationTest extends ApplicationTestCase
{
    /**
     * @before
     */
    public function preconditions()
    {
        $this->given(new ApplicationWasStarted());
    }

    /**
     * @test
     */
    public function specifying_valid_account_information()
    {
        $this->when(
            new SpecifyApplicationInformation(
                $this->aggregateRootId,
                'Frank',
                'de Jonge',
                'info@acme.co'
            )
        )->then(
            new ApplicationInformationWasSpecified()
        );
    }

    /**
     * @test
     * @dataProvider incompleteAccountInformation
     */
    public function specify_incomplete_information(string $firstName, string $lastName, string $email)
    {
        $this->when(
            new SpecifyApplicationInformation(
                $this->aggregateRootId,
                $firstName,
                $lastName,
                $email
            )
        )->expectToFail(new SorryApplicationInformationIsNotComplete());
    }

    public function incompleteAccountInformation()
    {
        yield ['', 'de Jonge', 'info@info.info'];
        yield ['Frank', '', 'info@info.info'];
        yield ['Frank', 'de Jonge', ''];
    }

    /**
     * @test
     */
    public function specifying_an_invalid_email()
    {
        $this->when(
            new SpecifyApplicationInformation(
                $this->aggregateRootId,
                $ref = Uuid::uuid4(),
                'Frank',
                'de Jonge',
                'not a valid email'
            )
        )->expectToFail(new SorryEmailIsInvalid());
    }
}