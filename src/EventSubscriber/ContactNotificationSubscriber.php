<?php

/*
 * Contact subscriber
 */

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\ContactCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Notifies post's author about new comments.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class ContactNotificationSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $translator;
    private $urlGenerator;
    private $sender;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator, string $sender)
    {
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->sender = $sender;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactCreatedEvent::class => 'onContactCreated',
        ];
    }

    public function onContactCreated(ContactCreatedEvent $event): void
    {
        /** @var Contact $Contact */
        $contact = $event->getContact();
        $contactName = $contact->getFullname();

        $subject = "Registration Notification";
        $body = "Hi {$contactName}. You have succesfully been registered. Enjoy!";

        // See https://symfony.com/doc/current/mailer.html
        $email = (new Email())
            ->from($this->sender)
            ->to($contact->getEmail())
            ->subject($subject)
            ->html($body)
        ;
        $this->mailer->send($email);
    }
}
