<?php

namespace App\DataFixtures;

use App\Entity\Discounts;
use Doctrine\Persistence\ObjectManager;

class DiscountsFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Discounts::class, 5, function (Discounts $discounts) {
            $discounts
                ->setValue($this->faker->numberBetween(15,80))
                ->setDescription($this->faker->paragraph)
            ;
            if ($this->faker->boolean(60)) {
                $discounts
                    ->setStartDate($this->faker->dateTimeBetween('-10 days', '-1 days'))
                    ->setEndDate($this->faker->dateTimeBetween('1 days', '10 days'))
                ;
            }
        });
    }
}
