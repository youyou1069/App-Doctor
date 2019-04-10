<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin =  new Admin();
        $admin ->setUsername('demo');

        $hash = $this->encoder->encodePassword($admin,'demo');
        $admin->setPassword($hash);
//        $admin->addRole("ROLE_ADMIN");

        //($this->encoder->encodePassword($user, 'demo'));
        $manager->persist($admin);

        $manager->flush();
    }
}
