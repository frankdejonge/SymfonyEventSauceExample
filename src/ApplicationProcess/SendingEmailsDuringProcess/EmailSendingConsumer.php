<?php

namespace AcmeCompany\ApplicationProcess\SendingEmailsDuringProcess;

use AcmeCompany\ApplicationProcess\ApplicationInformationWasSpecified;
use AcmeCompany\ApplicationProcess\ApplicationWasApproved;
use AcmeCompany\ApplicationProcess\ApplicationWasDeclined;
use App\PersonalInformation\PersonalInformationRepository;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use Ramsey\Uuid\Uuid;
use Swift_Mailer;
use Swift_Message;

class EmailSendingConsumer implements Consumer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var PersonalInformationRepository
     */
    private $personalInformation;

    public function __construct(Swift_Mailer $mailer, PersonalInformationRepository $personalInformation)
    {
        $this->mailer = $mailer;
        $this->personalInformation = $personalInformation;
    }

    public function handle(Message $message)
    {
        $event = $message->event();

        if ($event instanceof ApplicationInformationWasSpecified) {
            $email = new Swift_Message();
            $email->setTo('admin@symfony-app.tld');
            $email->setFrom('system@symfony-app.tld');
            $email->setBody("
Hi!

You have a new application to review:

    http://127.0.0.1:8000/review/{$message->aggregateRootId()->toString()}
    
Greetings from The System
");
            $this->mailer->send($email);
        } elseif ($event instanceof ApplicationWasApproved) {
            $email = new Swift_Message();
            $recipientInfo = $this->recipientInfo($message->aggregateRootId());
            $email->setTo($recipientInfo['email'], "{$recipientInfo['firstName']} {$recipientInfo['lastName']}");
            $email->setFrom('system@symfony-app.tld');
            $email->setBody("
Hi {$recipientInfo['firstName']}!

Your application has been approved!
    
Greetings from The System
");
            $this->mailer->send($email);
        } elseif ($event instanceof ApplicationWasDeclined) {
            $email = new Swift_Message();
            $recipientInfo = $this->recipientInfo($message->aggregateRootId());
            $email->setTo($recipientInfo['email'], "{$recipientInfo['firstName']} {$recipientInfo['lastName']}");
            $email->setFrom('system@symfony-app.tld');
            $email->setBody("
Hello {$recipientInfo['firstName']},

We regret to inform you your application has been declined due to:

    {$event->reason()}

- from The System
");
            $this->mailer->send($email);
        }
    }

    private function recipientInfo(AggregateRootId $aggregateRootId): array
    {
        $privateInformation = $this->personalInformation->retrieve(Uuid::fromString($aggregateRootId->toString()));

        return $privateInformation->personalInformation();
    }
}