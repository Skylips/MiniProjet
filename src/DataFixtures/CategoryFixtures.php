<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $category1 = new Category();
        $category1->setLabel('SPORT');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setLabel('MUSIQUE');
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setLabel('CINEMA');
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setLabel('CULINAIRE');
        $manager->persist($category4);

        $category5 = new Category();
        $category5->setLabel('THEATRE');
        $manager->persist($category5);

        $category6 = new Category();
        $category6->setLabel('SORTIE COLLECTIVE');
        $manager->persist($category6);

        $manager->flush();

        $this->addReference('SPORT',$category1);
        $this->addReference('MUSIQUE',$category2);
        $this->addReference('CINEMA',$category3);
        $this->addReference('CULINAIRE',$category4);
        $this->addReference('THEATRE',$category5);
        $this->addReference('SORTIE COLLECTIVE',$category6);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
