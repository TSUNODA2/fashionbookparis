<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 16; $i++)
        {
            $post = new Post();
            $post->setTitle($faker->sentence());
            $post->setDescription($faker->paragraphs(3, true));
            $post->setImage($faker->image('public/images/posts', 1280, 720, 'animals', false));
            $manager->persist($post);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
