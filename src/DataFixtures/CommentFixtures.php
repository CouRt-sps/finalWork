<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Products;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Comment::class, 60, function (Comment $comment) {

            $comment
                ->setUser($this->getRandomReference(User::class))
                ->setContent($this->faker->paragraph)
                ->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 day'))
                ->setProduct($this->getRandomReference(Products::class))
            ;
        });
    }

    public function getDependencies()
    {
        return [
            ProductsFixtures::class,
        ];
    }
}
