<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Code le mot de passe
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $role = ["ROLE_USER"];
            $user->setRoles($role);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
//    public function verifyUserEmail(Request $request): Response
//    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//
//        // validate email confirmation link, sets User::isVerified=true and persists
//        try {
//            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
//        } catch (VerifyEmailExceptionInterface $exception) {
//            $this->addFlash('verify_email_error', $exception->getReason());
//
//            return $this->redirectToRoute('app_register');
//        }
//
//        // @TODO Change the redirect on success and handle or remove the flash message in your templates
//        $this->addFlash('success', 'Your email address has been verified.');
//
//        return $this->redirectToRoute('app_register');
//    }
}
