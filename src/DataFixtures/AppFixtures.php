<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Chapter;
use App\Entity\Comment;
use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\PostNote;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");
        $userAdmin = new User();
        $userAdmin
            ->setFirstname("Quentin")
            ->setLastname("Sommesous")
            ->setRoles(["ROLE_ADMIN"])
            ->setEmail("sl0ders@gmail.com")
            ->setState("administrator")
            ->setPassword($this->hasher->hashPassword($userAdmin, "258790"));
        $manager->persist($userAdmin);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $user
                ->setEmail($faker->email)
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setPassword($this->hasher->hashPassword($user, "password"))
                ->setRoles(["ROLE_USER"])
                ->setState("registered");
            $manager->persist($user);

            for ($a = 0; $a < 2; $a++) {
                $address = new Address();
                $address
                    ->setIsEnabled(true)
                    ->setCity($faker->city)
                    ->setCountry($faker->country)
                    ->setPostalCode($faker->postcode)
                    ->setPhoneNumber($faker->phoneNumber)
                    ->setStreet($faker->streetAddress)
                    ->setResident($user);
                $manager->persist($address);
            }

            for ($c = 0; $c < 10; $c++) {
                $chapter = new Chapter();
                $chapter
                    ->setIsEnabled(true)
                    ->setName($faker->sentence(3))
                    ->setCreatedAt(new DateTimeImmutable($faker->date("d-m-Y")))
                    ->setNumber($c)
                    ->setAuthor($user);
                $manager->persist($chapter);

                for ($p = 0; $p < 10; $p++) {
                    $post = new Post();
                    $post
                        ->setAuthor($user)
                        ->setCreatedAt(new DateTimeImmutable($faker->date("d-m-Y")))
                        ->setIsEnabled(true)
                        ->setChapter($chapter)
                        ->setContent($faker->text)
                        ->setTitle($faker->sentence(2));
                    $manager->persist($post);

                    for ($n = 0; $n < 10; $n++) {
                        $note = new PostNote();
                        $note
                            ->setPost($post)
                            ->setCreatedAt(new DateTimeImmutable($faker->date("d-m-Y")))
                            ->setNote($faker->numberBetween(1, 10))
                            ->setNotifier($user);
                        $manager->persist($note);
                    }
                    $notification = new Notification();
                    $notification
                        ->setContent($faker->text)
                        ->setCreatedAt(new DateTimeImmutable($faker->date("d-m-Y")))
                        ->setIdPath($p)
                        ->setPath("admin_post_show")
                        ->setIsRead($faker->boolean())
                        ->setReceiver($userAdmin);
                    $manager->persist($notification);
                    for ($co = 0; $co < 6; $co++) {
                        $comment = new Comment();
                        $comment
                            ->setCreatedAt(new DateTimeImmutable($faker->date("d-m-Y")))
                            ->setContent($faker->text)
                            ->setIsEnabled(true)
                            ->setAuthor($user)
                            ->setPost($post);
                        $manager->persist($comment);
                    }
                }
            }
        }
        $manager->flush();
    }
}