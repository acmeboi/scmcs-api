<?php

namespace App\Controller;

use App\ApiResource\PasswordResetRequest;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetRequestController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private EmailService $emailService
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return new JsonResponse(
                ['error' => 'Email is required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Find user by email
        $user = $this->userRepository->findOneByEmail($email);

        // For security, always return success message even if user doesn't exist
        // This prevents email enumeration attacks
        if (!$user) {
            return new JsonResponse([
                'message' => 'If an account exists with this email, a password reset link has been sent.',
            ], Response::HTTP_OK);
        }

        // Check if user is enabled
        if (!$user->isEnabled()) {
            return new JsonResponse([
                'message' => 'If an account exists with this email, a password reset link has been sent.',
            ], Response::HTTP_OK);
        }

        // Generate password reset token
        $resetToken = bin2hex(random_bytes(32));
        $user->setPasswordResetToken($resetToken);
        $user->setPasswordResetExpiresAt(new \DateTime('+24 hours'));
        $user->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        // Send password reset email
        try {
            $this->emailService->sendPasswordResetEmail($user, $resetToken);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            // In production, you might want to queue this or handle differently
            error_log('Failed to send password reset email: ' . $e->getMessage());
        }

        return new JsonResponse([
            'message' => 'If an account exists with this email, a password reset link has been sent.',
        ], Response::HTTP_OK);
    }
}

