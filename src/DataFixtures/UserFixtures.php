<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private  $userPasswordHasher;

    /**
     * UserFixtures constructor.
     * @param UserPasswordHasherInterface userPasswordHasher
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("admin@gmail.com")
            ->setFirstName("Admin")
            ->setLastName("nomAdmin")
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    "admin"
                )
            )
            ->setRoles([User::ROLE_ADMIN]);
        $manager->persist($user);

        $user = new User();
        $user->setEmail("user@gmail.com")
            ->setFirstName("User")
            ->setLastName("nomUser")
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    "user"
                )
            );
        $manager->persist($user);

        $manager->flush();
    }
}
