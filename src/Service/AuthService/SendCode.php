<?php





namespace App\Service\AuthService;

    use Doctrine\ORM\EntityManagerInterface;
    use App\Entity\MailVeryfication;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;
    use App\Repository\MailVeryficationRepository;
    use App\Command\Create\CreateMailVeryfication\CreateMailCommand;

    #[AsService]
    class SendCode
    {
        private EntityManagerInterface $entityManager;
        private MailerInterface $mailer;
        private MailVeryficationRepository $mailRepository;
        private CreateMailCommand $createMailCommand;

        public function __construct(
            EntityManagerInterface $entityManager,
            MailerInterface $mailer,
            MailVeryficationRepository $mailRepository,
            CreateMailCommand $createMailCommand
        ) {
            $this->entityManager = $entityManager;
            $this->mailer = $mailer;
            $this->mailRepository = $mailRepository;
            $this->createMailCommand = $createMailCommand;
        }

        public function send($email)
        {
            $code = random_int(100000, 999999);
            $existing = $this->mailRepository->isMailUnique($email);

            if ($existing) {
               $this->createMailCommand->existingSetMail($existing, $email, $code);
            } else {
                $this->createMailCommand->createMail($email, $code);
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
