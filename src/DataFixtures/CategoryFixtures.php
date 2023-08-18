<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Category::class, 12, function (Category $category) {
            $category
                ->setName('Category' . rand(1,12))
                ->setSortIndex(rand(1,10))
                ->setIconFilename('assets/img/icons/departments/' . rand(1,12) . '.svg')
                ->setIsActive(true)
                ->setFavorites($this->faker->boolean())
            ;
        });
    }
}
