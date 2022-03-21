<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        for ($p = 0; $p < 100; $p++) {
            $post = new Post;
            $post->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraphs(5, true))
                ->setMainPicture($faker->imageUrl(400, 400, true))
                ->setCreatedAt($faker->dateTime())
                ->setLastUpdate($faker->dateTime());

            $manager->persist($post);
        }

        $manager->flush();
    }
}
