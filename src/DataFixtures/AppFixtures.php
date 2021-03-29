<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
/**
 * Indique l'ordre d'exécution des fixtures
 * Si jamais , par exemple, EventFixture s'execute avant UserFixture,
 * il n'existera pas de User à rentrer dans un objet Event, donc il y aura une erreur
 */
class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            EvenementFixtures::class,
            CommentaireFixtures::class,
        ];
    }
}
