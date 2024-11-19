<?php





namespace App\Service;

    use App\Singleton\EntityManagerSingleton;
    use App\Entity\MailVeryfication;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;
    use App\Facade\MailVeryficationFacade;

    #[AsService]
    class SendCode
    {
        private EntityManagerSingleton $entityManager;
        private MailerInterface $mailer;
        private MailVeryficationFacade $mail;

        public function __construct(
            MailVeryficationFacade $mail,
            EntityManagerSingleton $entityManager,
            MailerInterface $mailer
        ) {
            $this->mail = $mail;
            $this->entityManager = $entityManager;
            $this->mailer = $mailer;
        }

        public function send($email)
        {
            $code = random_int(100000, 999999);
            $existing = $this->mail->isMailUnique($email);

            if ($existing) {
                $existing->setEmail($email);
                $existing->setCode($code);
                $existing->setCreatedAt(new \DateTime());
                $this->entityManager->persist($existing);
            } else {
                $this->mail->createMail($email, $code);
            }

            try {
                $this->entityManager->flush();
                $emailMessage = (new Email())
                    ->from('LexaDev@example.com')
                    ->to($email)
                    ->subject('Your verification code:')
                    ->text('Your verification code: ' . $code);

                $this->mailer->send($emailMessage);
                return true;

            } catch (\Exception $e) {
                throw new \InvalidArgumentException("Произошла ошибка при выполнении кода: " . $e->getMessage());
            }
        }
    }
