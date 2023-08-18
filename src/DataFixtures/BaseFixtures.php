<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

abstract class BaseFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    protected $manager;
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        $this->loadData($manager);
    }
    abstract function loadData(ObjectManager $manager);

    protected function create(string $classname, callable $factory): object
    {
        $entity = new $classname();
        $factory($entity);

        $this->manager->persist($entity);

        return $entity;
    }
    protected function createMany(string $classname, int $count, callable $factory): void
    {
        for ($i=0; $i < $count; $i++) {
            $this->create($classname, $factory);
        }
        $this->manager->flush();
    }

    protected function getRandomReference(string $className): object
    {
        $repository = $this->manager->getRepository($className);
        $objects = $repository->findBy([], ['id' => 'ASC']);

        $randomIndex = mt_rand(0, count($objects) - 1);

        return $objects[$randomIndex];
    }
}
