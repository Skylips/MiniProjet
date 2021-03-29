<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Evenement;
use App\Entity\User;
use App\Form\CommentaireType;
use App\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class BlogController extends AbstractController
{
    public function index()
    {
        $event = $this->getDoctrine()->getRepository(Evenement::class)->findBy(
            ['isPublished' => true],
            ['dateEvent' => 'asc']
        );

        return $this->render('site/index.html.twig',
            ['evenements' => $event]
        );
    }

    public function add(Request $request, UserInterface $user)
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $event = new Evenement();
        $form = $this->createForm(EvenementType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setLastUpdateDate(new \DateTime());
            $event->setCreateur($user);

            //Vérifie si l'event a une image
            if ($event->getPicture() !== null) {
                $file = $form->get('picture')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $event->setPicture($fileName);
            }

            //Si l'event est publié, met la date d'aujourd'hui pour la variable $publicationDate
            if ($event->getIsPublished()) {
                $event->setPublicationDate(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('site/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function show(Evenement $evenement, UserInterface $user)
    {
        return $this->render('site/show.html.twig', [
            'evenement' => $evenement,
            'commentaires' => $evenement->getCommentaires(),
            'user' => $user
        ]);
    }

//    /**
//     * @IsGranted("ROLE_ADMIN")
//     */
    public function edit(Evenement $event, Request $request)
    {
        $oldPicture = $event->getPicture();

        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setLastUpdateDate(new \DateTime());

            //Si l'event est publié, met la date d'aujourd'hui pour la variable $publicationDate
            if ($event->getIsPublished()) {
                $event->setPublicationDate(new \DateTime());
            }

            //Vérifie si l'event a une image
            if ($event->getPicture() !== null && $event->getPicture() !== $oldPicture) {
                $file = $form->get('picture')->getData();
                $fileName = uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $event->setPicture($fileName);
            } else {
                $event->setPicture($oldPicture);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('site/edit.html.twig', [
            'evenement' => $event,
            'form' => $form->createView()
        ]);
    }

    public function remove($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(Evenement::class)->find($id);
        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    public function admin()
    {
        //On récupère tous les événements
        $events = $this->getDoctrine()->getRepository(Evenement::class)->findBy(
            [],
            ['lastUpdateDate' => 'DESC']
        );

        //On récupère tous les users
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        //On récupère tous les commentaires
        $comments = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'evenements' => $events,
            'users' => $users,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/change-locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request){
        //On stocke la langue demandé
        $request->getSession()->set('_locale',$locale);

        //On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}