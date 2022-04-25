<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\ProductMarket;
use App\Entity\ProductCategory;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        for ($c = 0; $c < 5; $c++) {

            $category = new ProductCategory;
            $category->setNameCategory($faker->department)
                ->setSlugCategory(strtolower($this->slugger->slug($category->getNameCategory())));

            $manager->persist($category);

            for ($p = 0; $p <= 15; $p++) {

                $product = new ProductMarket;
                $product->setProductNamet($faker->productName)
                    ->setProductDescription($faker->paragraph())
                    ->setProductPrice($faker->price(4000, 20000))
                    ->setProductPicture($faker->imageUrl(100, 100))
                    ->setProductSlug(strtolower($this->slugger->slug($product->getProductNamet())))
                    ->setProductCategory($category)
                    ->setCreatedAt(new \DateTimeImmutable());

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
