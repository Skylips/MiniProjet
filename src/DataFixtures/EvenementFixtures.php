<?php

namespace App\DataFixtures;

use App\Entity\Evenement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
/**
 * Permet d'ajouter des evenements à la BDD
 */
class EvenementFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $event1 = new Evenement();
        $event1->setPicture("piscine.jpg");
        $event1->setTitle('Sortie Piscine');
        $event1->setContent('Sortie piscine ce week-end');
        $date = date_create(date("2021-03-24 16:48:06"));
        $event1->setPublicationDate($date);
        $date2 = date_create(date("2021-03-24 16:48:06"));
        $event1->setLastUpdateDate($date2);
        $event1->setIsPublished(true);
        $event1->addCategorie($this->getReference('SORTIE COLLECTIVE'));
        $date3 = date_create(date("2021-03-26"));
        $event1->setDateEvent($date3);
        $event1->setCreateur($this->getReference('admin'));
        $event1->setPlace('Nantes');
        $manager->persist($event1);

        $event2 = new Evenement();
        $event2->setPicture("cinema.jpg");
        $event2->setTitle('Sortie Cinema');
        $event2->setContent('Sortie Cinema ce week-end');
        $date4 = date_create(date("2021-03-23 16:58:06"));
        $event2->setLastUpdateDate($date4);
        $event2->setIsPublished(false);
        $event2->addCategorie($this->getReference('CINEMA'));
        $date5 = date_create(date("2021-03-27"));
        $event2->setDateEvent($date5);
        $event2->setCreateur($this->getReference('user1'));
        $event2->setPlace('Nantes');
        $manager->persist($event2);

        $this->addReference('event1',$event1);
        $this->addReference('event2',$event2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
