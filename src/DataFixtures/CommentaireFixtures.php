<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class CommentaireFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $comment1 = new Commentaire();
        $comment1->setContenu('Bien vu !!!');
        $date= date_create(date("2021-03-25 14:33:14"));
        $comment1->setCreatedAt($date);
        $comment1->setCreator($this->getReference('admin'));
        $comment1->setEvent($this->getReference('event1'));
        $manager->persist($comment1);

        $manager->flush();

        $this->addReference('comment1',$comment1);
    }
}
