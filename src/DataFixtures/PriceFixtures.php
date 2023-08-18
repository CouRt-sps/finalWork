<?php

namespace App\DataFixtures;

use App\Entity\Price;
use App\Entity\Products;
use App\Entity\Seller;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PriceFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $products = $manager->getRepository(Products::class)->findAll();

        foreach ($products as $product) {
            $priceCount = mt_rand(1, 3);

            $this->createMany(Price::class, $priceCount, function (Price $price) use ($product) {
                $price
                    ->setPrice($this->faker->numberBetween(1,100))
                    ->setProduct($product)
                    ->setSeller($this->getRandomReference(Seller::class))
                ;
            });
        }
    }

    public function getDependencies()
    {
        return [
            SellerFixtures::class,
            ProductsFixtures::class,
        ];
    }
}
