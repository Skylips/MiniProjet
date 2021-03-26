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

            if ($event->getIsPublished()) {
                $event->setPublicationDate(new \DateTime());
            }

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
        $events = $this->getDoctrine()->getRepository(Evenement::class)->findBy(
            [],
            ['lastUpdateDate' => 'DESC']
        );

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

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
//      On stocke la langue demandé
        $request->getSession()->set('_locale',$locale);

//      On revient sur la page précédente
        return $this->redirectToRoute('homepage');
//        return $this->render($request->headers->get('referer'));
    }
}