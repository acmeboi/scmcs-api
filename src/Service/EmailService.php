<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private UrlGeneratorInterface $urlGenerator,
        private string $appUrl
    ) {
    }

    public function sendWelcomeEmail(User $user, string $defaultPassword, string $resetToken): void
    {
        $resetUrl = $this->appUrl . '/api/password/update?token=' . $resetToken;

        $email = (new Email())
            ->from('info@scmcs.org')
            ->to($user->getEmail())
            ->subject('Welcome to SCMCS - Account Created')
            ->html($this->twig->render('emails/welcome.html.twig', [
                'user' => $user,
                'defaultPassword' => $defaultPassword,
                'resetUrl' => $resetUrl,
                'resetToken' => $resetToken,
            ]));

        $this->mailer->send($email);
    }
}

