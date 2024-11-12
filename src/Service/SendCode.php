<?php





    namespace App\Service;

    use Doctrine\ORM\EntityManagerInterface;
    use App\Entity\MailVeryfication;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;
    use App\Service\CheckTableMail;
    use Psr\Log\LoggerInterface;

    #[AsService]
    class SendCode
    {
        private EntityManagerInterface $entityManager;
        private MailerInterface $mailer;
        private CheckTableMail $checkTableMail;
        private LoggerInterface $logger;

        public function __construct(
            CheckTableMail $checkTableMail,
            EntityManagerInterface $entityManager,
            MailerInterface $mailer,
            LoggerInterface $logger
        ) {
            $this->checkTableMail = $checkTableMail;
            $this->entityManager = $entityManager;
            $this->mailer = $mailer;
            $this->logger = $logger;
        }

        public function send($email)
        {
            $code = random_int(100000, 999999);
            $existing = $this->checkTableMail->check($email);

            if ($existing) {
                $existing->setEmail($email);
                $existing->setCode($code);
                $existing->setCreatedAt(new \DateTime());
                $this->entityManager->persist($existing);
            } else {
                $newVerification = new MailVeryfication();
                $newVerification->setEmail($email);
                $newVerification->setCode($code);
                $newVerification->setCreatedAt(new \DateTime());
                $this->entityManager->persist($newVerification);
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
