<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailerTest extends AbstractController
{
    private MailerInterface $mailer;


    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    #[Route('api/test/mailer')]
    public function test(): Response
    {

        $email = (new Email())
            ->from('sender@example.com')
            ->to('recipient@example.com')
            ->subject('Test Email')
            ->text('This is a test email sent via Symfony and MailHog.');

        try {
            $this->mailer->send($email);
            return new Response('Email sent successfully.');
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage());
        }
    }
}
