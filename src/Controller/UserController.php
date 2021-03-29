<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Evenement;
use App\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    public function ownEvent(UserInterface $user)
    {
        //On récupère tous les événements
        $events = $this->getDoctrine()->getRepository(Evenement::class)->findBy(
            [],
            ['lastUpdateDate' => 'DESC']
        );

        //On récupère tous les commentaires
        $commentary = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(
            [],[]
        );

        return $this->render('site/ownEvent.html.twig', [
            'evenements' => $events,
            'user' => $user,
            'comments' => $commentary
        ]);
    }

    public function addComment($id, Request $request, UserInterface $user)
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $comment = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setCreator($user);

            $entityManager = $this->getDoctrine()->getManager();
            $event = $entityManager->getRepository(Evenement::class)->find($id);
            $comment->setEvent($event);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($request->headers->get('referer'));
//            return $this->redirect($_SERVER['HTTP_REFERER']);

        }

        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(Evenement::class)->find($id);

        return $this->render('site/addComment.html.twig', [
            'form' => $form->createView(),
            'evenement' => $event
        ]);
    }

    public function removeComment($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Commentaire::class)->find($id);
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}