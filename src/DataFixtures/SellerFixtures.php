<?php

namespace App\DataFixtures;

use App\Entity\Seller;
use Doctrine\Persistence\ObjectManager;

class SellerFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Seller::class, 5, function (Seller $seller) {
            $seller
                ->setSeller($this->faker->company)
                ->setDescription($this->faker->paragraph)
                ->setPhone($this->faker->phoneNumber)
                ->setAddress($this->faker->address)
                ->setEmail($this->faker->email)
                ->setImageSeller('assets/img/content/home/bigGoods.png')
            ;
        });
    }
}
