<?php


namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class BlogController extends AbstractController
{
    public function index()
    {
        $event = $this->getDoctrine()->getRepository(Evenement::class)->findBy(
            ['isPublished' => true],
            ['publicationDate' => 'desc']
        );

        return $this->render('site/index.html.twig', ['evenements' => $event]);
    }

    public function add(Request $request)
    {
        $event = new Evenement();
        $form = $this->createForm(EvenementType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setLastUpdateDate(new \DateTime());

            if ($event->getPicture() !== null) {
                $file = $form->get('picture')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'), // Le dossier dans le quel le fichier va etre charger
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

            $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager
            $em->persist($event); // On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); // On execute la requete

            return new Response('L\'evenement a bien été enregistrer.');
        }

        return $this->render('site/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function show(Evenement $evenement)
    {
        return $this->render('site/show.html.twig', [
            'evenement' => $evenement
        ]);
    }

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

            return new Response('L\'evenement a bien été modifie.');
        }

        return $this->render('site/edit.html.twig', [
            'evenement' => $event,
            'form' => $form->createView()
        ]);
    }

    public function remove($id)
    {
        return new Response('<h1>Delete evenement: ' .$id. '</h1>');
    }
}