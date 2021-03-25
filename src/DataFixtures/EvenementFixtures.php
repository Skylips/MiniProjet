<?php

namespace App\DataFixtures;

use App\Entity\Evenement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EvenementFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $event1 = new Evenement();
        $event1->setTitle('Sortie Piscine');
        $event1->setContent('Sortie piscine ce week-end');
        $event1->setPublicationDate('2021-03-24 16:48:06');
        $event1->setLastUpdateDate('2021-03-25 16:58:06');
        $event1->setIsPublished(true);
        $event1->addCategorie($this->getReference('SORTIE COLLECTIVE'));
        $event1->setDateEvent('2021-03-26');
        $event1->setCreateur($this->getReference('admin'));
        $event1->setPlace('Nantes');
        $manager->persist($event1);

        $event2 = new Evenement();
        $event2->setTitle('Sortie Cinema');
        $event2->setContent('Sortie Cinema ce week-end');
        $event2->setLastUpdateDate('2021-03-23 16:58:06');
        $event2->setIsPublished(false);
        $event1->addCategorie($this->getReference('CINEMA'));
        $event2->setDateEvent('2021-03-27');
        $event2->setCreateur($this->getReference('user1'));
        $event2->setPlace('Nantes');
        $manager->persist($event2);

        $this->addReference('event1',$event1);
        $this->addReference('event2',$event2);

        $manager->flush();
    }
}
