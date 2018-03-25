<?php

namespace App\Controller;

use AcmeCompany\ApplicationProcess\ApplicationId;
use AcmeCompany\ApplicationProcess\ApproveApplication;
use AcmeCompany\ApplicationProcess\DeclineApplication;
use function compact;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends Controller
{
    /**
     * @Route("/review-done", name="application.review_done", methods={"GET"})
     */
    public function reviewDone()
    {
        return $this->render('application/review_done.html.twig');
    }

    /**
     * @Route("/review/{applicationId}", name="application.review", methods={"GET"})
     */
    public function review(string $applicationId)
    {
        $application = $this->get('account.pending_applications')
            ->findForApplication(Uuid::fromString($applicationId));

        return $this->render('application/review.html.twig', compact('applicationId', 'application'));
    }

    /**
     * @Route("/review/{applicationId}", name="application.process_review", methods={"POST"})
     */
    public function processReview(string $applicationId, Request $request)
    {
        $action = $request->request->get('review');
        $applicationId = ApplicationId::fromString($applicationId);
        $command = $action === 'approve'
            ? new ApproveApplication($applicationId)
            : new DeclineApplication($applicationId, $request->request->get('decline_reason', ''));

        $this->get('application.command_handler')->handle($command);

        return $this->redirectToRoute('application.review_done');
    }
}