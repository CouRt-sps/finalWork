<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Discounts;
use App\Entity\Products;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductsFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();

        foreach ($categories as $category) {
            $productCount = mt_rand(1, 3);

            $this->createMany(Products::class, $productCount, function (Products $products) use ($category) {
                $products
                    ->setProduct($this->faker->name)
                    ->setDescription($this->faker->title)
                    ->setBody($this->faker->text)
                    ->setImageFilename('assets/img/content/home/bigGoods.png')
                    ->setCategory($category)
                    ->setQuantityBuy($this->faker->numberBetween(1, 15))
                    ->setLimitedEdition($this->faker->boolean())
                    ->setSortIndex(rand(1, 10));

                if ($this->faker->boolean(50)) {
                    $products
                        ->setDiscounts($this->getRandomReference(Discounts::class));
                }
            });
        }
    }
    public function getDependencies()
    {
        return [
            DiscountsFixtures::class,
        ];
    }
}
