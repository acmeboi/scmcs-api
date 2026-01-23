<?php

namespace App\Controller;

use App\ApiResource\SignUpRequest;
use App\Entity\Member;
use App\Entity\User;
use App\Repository\MemberRepository;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SignUpController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MemberRepository $memberRepository,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private EmailService $emailService,
        private UrlGeneratorInterface $urlGenerator,
        private string $appUrl
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

        // Check if User already exists
        $existingUser = $this->userRepository->findOneByEmail($email);
        if ($existingUser) {
            return new JsonResponse(
                ['error' => 'User account already exists for this email'],
                Response::HTTP_CONFLICT
            );
        }

        // Find Member by email
        $member = $this->memberRepository->createQueryBuilder('m')
            ->where('m.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$member) {
            return new JsonResponse(
                ['error' => 'Member profile not found for this email'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Check if Member already has a User account
        if ($member->getUser()) {
            return new JsonResponse(
                ['error' => 'This member already has a user account'],
                Response::HTTP_CONFLICT
            );
        }

        // Generate default password
        $defaultPassword = bin2hex(random_bytes(8)); // 16 character random password

        // Create User account
        $user = new User();
        $user->setEmail($email);
        $user->setMember($member);
        $user->setEnabled(true);
        $user->setMustChangePassword(true);
        
        // Hash the default password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $defaultPassword);
        $user->setPassword($hashedPassword);

        // Generate password reset token
        $resetToken = bin2hex(random_bytes(32));
        $user->setPasswordResetToken($resetToken);
        $user->setPasswordResetExpiresAt(new \DateTime('+24 hours'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Generate password reset URL
        $resetUrl = 'https://fpb.scmcs.org/password-reset?token=' . $resetToken;
        
        // Send welcome email
        $emailSent = false;
        try {
            $this->emailService->sendWelcomeEmail($user, $defaultPassword, $resetToken);
            $emailSent = true;
        } catch (\Exception $e) {
            // Log error but don't fail the request
            // In production, you might want to queue this or handle differently
        }

        return new JsonResponse([
            'message' => $emailSent 
                ? 'User account created successfully. Welcome email sent.' 
                : 'User account created successfully. Email delivery failed - please use the credentials below.',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ],
            'credentials' => [
                'defaultPassword' => $defaultPassword,
                'passwordResetToken' => $resetToken,
                'passwordResetUrl' => $resetUrl,
                'expiresAt' => $user->getPasswordResetExpiresAt()?->format('Y-m-d H:i:s'),
            ],
            'note' => 'Please save these credentials securely. The password reset token expires in 24 hours.',
        ], Response::HTTP_CREATED);
    }
}

