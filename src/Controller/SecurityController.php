<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    public function removeUser($id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id); //Cherche l'user via son ID
        $entityManager->remove($user); //Supprime l'user
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    public function verifyUser($id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id); //Cherche l'user via son ID
        $user->setIsVerified(true); //Met l'user en vérifié
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    public function adminAuth($id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);//Cherche l'user via son ID
        $tempo = $user->getRoles();
        if($tempo[0] == 'ROLE_USER'){ //Vérifie le role actuel du user
            $user->setRoles(['ROLE_ADMIN']);
        }else{
            $user->setRoles(['ROLE_USER']);
        }
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
