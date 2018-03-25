<?php

namespace App\Controller;

use AcmeCompany\ApplicationProcess\ApplicationId;
use AcmeCompany\ApplicationProcess\SpecifyApplicationInformation;
use AcmeCompany\ApplicationProcess\StartApplication;
use function compact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends Controller
{
    /**
     * @Route("/apply", name="application.start_form", methods={"GET"})
     */
    public function startForm()
    {
        return $this->render('application/start.html.twig');
    }

    /**
     * @Route("/apply", name="application.process_start_form", methods={"POST"})
     */
    public function processStartForm()
    {
        $applicationId = ApplicationId::create();
        $this->handleCommand(new StartApplication($applicationId));

        return $this->redirectToRoute('application.information_form', ['applicationId' => $applicationId->toString()]);
    }

    /**
     * @Route("/apply/{applicationId}/information", name="application.information_form", methods={"GET"})
     */
    public function informationForm(string $applicationId)
    {
        return $this->render('application/information.html.twig', compact('applicationId'));
    }

    /**
     * @Route("/apply/{applicationId}/information", name="application.process_information_form", methods={"POST"})
     */
    public function processInformationForm(string $applicationId, Request $request)
    {
        $this->handleCommand(new SpecifyApplicationInformation(
            ApplicationId::fromString($applicationId),
            $request->request->get('first_name', ''),
            $request->request->get('last_name', ''),
            $request->request->get('email', '')
        ));

        return $this->redirectToRoute('application.done');
    }

    /**
     * @Route("/done", name="application.done")
     */
    public function appliedThanks()
    {
        return $this->render('application/applied.html.twig');
    }

    private function handleCommand(object $command)
    {
        return $this->get('application.command_handler')->handle($command);
    }
}