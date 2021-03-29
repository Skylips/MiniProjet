<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
/**
 * Permet d'ajouter des utilisateurs à la BDD
 */
class UserFixtures extends Fixture //implements OrderedFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('Admin');
        $user1->setEmail('admin@gmail.com');
        $user1->setRoles(["ROLE_ADMIN"]);
        $password1 = $this->encoder->encodePassword($user1, 'aaaaaa');
        $user1->setPassword($password1);
        $user1->setIsVerified(true);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('User1');
        $user2->setEmail('User1@gmail.com');
        $user2->setRoles(["ROLE_USER"]);
        $password2 = $this->encoder->encodePassword($user2, 'bbbbbb');
        $user2->setPassword($password2);
        $user2->setIsVerified(true);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setUsername('User2');
        $user3->setEmail('User2@gmail.com');
        $user3->setRoles(["ROLE_USER"]);
        $password3 = $this->encoder->encodePassword($user3, 'cccccc');
        $user3->setPassword($password3);
        $user3->setIsVerified(false);
        $manager->persist($user3);

        $user4 = new User();
        $user4->setUsername('User3');
        $user4->setEmail('User3@gmail.com');
        $user4->setRoles(["ROLE_USER"]);
        $password4 = $this->encoder->encodePassword($user4, 'dddddd');
        $user4->setPassword($password4);
        $user4->setIsVerified(false);
        $manager->persist($user4);

        $manager->flush();

        $this->addReference('admin',$user1);
        $this->addReference('user1',$user2);
        $this->addReference('user2',$user3);
        $this->addReference('user3',$user4);
    }

//    public function getOrder() {
//        return 1;
//    }
}
