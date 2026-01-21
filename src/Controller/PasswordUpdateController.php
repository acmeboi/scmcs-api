<?php

namespace App\Controller;

use App\ApiResource\PasswordUpdateRequest;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordUpdateController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'] ?? $request->query->get('token');
        $newPassword = $data['newPassword'] ?? null;

        if (!$token) {
            return new JsonResponse(
                ['error' => 'Token is required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!$newPassword) {
            return new JsonResponse(
                ['error' => 'New password is required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (strlen($newPassword) < 8) {
            return new JsonResponse(
                ['error' => 'Password must be at least 8 characters long'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Find user by reset token
        $user = $this->userRepository->createQueryBuilder('u')
            ->where('u.passwordResetToken = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            return new JsonResponse(
                ['error' => 'Invalid or expired token'],
                Response::HTTP_NOT_FOUND
            );
        }

        // Check if token is expired
        if ($user->getPasswordResetExpiresAt() < new \DateTime()) {
            return new JsonResponse(
                ['error' => 'Token has expired'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Update password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $user->setPasswordResetToken(null);
        $user->setPasswordResetExpiresAt(null);
        $user->setMustChangePassword(false);
        $user->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Password updated successfully. You can now log in with your new password.',
        ], Response::HTTP_OK);
    }
}

