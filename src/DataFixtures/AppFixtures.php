<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $author = new User();
        $author->setEmail('abc@def.com');
        $author->setName('hachim');
        $author->setSurname('youssoufa');
        $author->setCategory('Particulier');
        $author->setVerified(true);
        $author->setPartnership(false);
        $author->setRoles(['ROLE_ADMIN']);
        $plaintextPassword = 'test123';

        $hashedPassword = $this->userPasswordHasher->hashPassword($author, $plaintextPassword);
        $author->setPassword($hashedPassword);
        $manager->persist($author);
        $images = ['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg', '9.jpg', '10.jpg', '12.jpg', '13.jpg', '14.jpg', '15.jpg', '16.jpg', '17.jpg', '18.jpg', '19.jpg', '20.jpg'];
        for ($i = 0; $i <= 16; $i++) {
            $image = $images[array_rand($images)];

            $post = new Post();
            $post->setTitle($faker->sentence());
            $post->setDescription($faker->paragraphs(5, true));
            $post->setImage($image);
            $post->setAuthor($author);
            $manager->persist($post);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
