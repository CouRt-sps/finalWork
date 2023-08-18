<?php

namespace App\DataFixtures;

use App\Entity\Feature;
use App\Entity\Products;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FeatureFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $products = $manager->getRepository(Products::class)->findAll();

        foreach ($products as $product) {

            $this->create(Feature::class, function (Feature $feature) use ($product) {
                $feature
                    ->setProduct($product)
                    ->setRating(rand(1,10))
                    ->setMatrix($this->faker->randomElement(['IPS', 'TN']))
                    ->setHeadphones($this->faker->boolean())
                    ->setWorkingHours(rand(3,8))
                ;
            });
        }

        $this->manager->flush();
    }
    public function getDependencies()
    {
        return [
            ProductsFixtures::class,
        ];
    }
}
