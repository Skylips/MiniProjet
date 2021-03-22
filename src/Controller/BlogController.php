<?php


namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class BlogController extends AbstractController
{
    public function index()
    {
        return $this->render('site/index.html.twig');
    }

    public function add()
    {
        $event = new Evenement();
        $form = $this->createForm(EvenementType::class, $event);

        return $this->render('site/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function show($url)
    {
        return $this->render('site/show.html.twig', [
            'slug' => $url
        ]);
    }

    public function edit($id)
    {
        return $this->render('site/edit.html.twig', [
            'slug' => $id
        ]);
    }

    public function remove($id)
    {
        return new Response('<h1>Delete article: ' .$id. '</h1>');
    }
}