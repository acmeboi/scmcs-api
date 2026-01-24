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
        $resetUrl = 'https://fpb.scmcs.org/password-reset?token=' . $resetToken;

        try {
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
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            error_log('Email transport error: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            error_log('Email sending error: ' . $e->getMessage());
            error_log('Exception class: ' . get_class($e));
            throw $e;
        }
    }

    public function sendPasswordResetEmail(User $user, string $resetToken): void
    {
        $resetUrl = 'https://fpb.scmcs.org/password-reset?token=' . $resetToken;

        try {
            $email = (new Email())
                ->from('info@scmcs.org')
                ->to($user->getEmail())
                ->subject('Password Reset Request - SCMCS')
                ->html($this->twig->render('emails/password-reset.html.twig', [
                    'user' => $user,
                    'resetUrl' => $resetUrl,
                    'resetToken' => $resetToken,
                ]));

            $this->mailer->send($email);
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            error_log('Email transport error: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            error_log('Email sending error: ' . $e->getMessage());
            error_log('Exception class: ' . get_class($e));
            throw $e;
        }
    }
}

