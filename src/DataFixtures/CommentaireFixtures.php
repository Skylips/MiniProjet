<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
/**
 * Permet d'ajouter des commentaires à la BDD
 */
class CommentaireFixtures extends Fixture implements DependentFixtureInterface
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

        $comment2 = new Commentaire();
        $comment2->setContenu('Hâte d\'y être !!!');
        $date2= date_create(date("2021-03-25 15:33:14"));
        $comment2->setCreatedAt($date2);
        $comment2->setCreator($this->getReference('user1'));
        $comment2->setEvent($this->getReference('event1'));
        $manager->persist($comment2);

        $comment3 = new Commentaire();
        $comment3->setContenu('Je voulais voir ce film !!!');
        $date2= date_create(date("2021-03-27 14:20:14"));
        $comment3->setCreatedAt($date2);
        $comment3->setCreator($this->getReference('user2'));
        $comment3->setEvent($this->getReference('event2'));
        $manager->persist($comment3);

        $manager->flush();

        $this->addReference('comment1',$comment1);
    }

    public function getDependencies()
    {
        return [
            EvenementFixtures::class,
            UserFixtures::class
        ];
    }
}
