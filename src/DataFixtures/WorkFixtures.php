<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Work;
use Faker\Factory;

class WorkFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 5; $i++) { 
            $work = new Work();

            $work->setCompany($faker->company());
            $work->setLabel($faker->jobTitle());

            $manager->persist($work);

            $this->addReference('work_' . $i, $work);
        }

        $manager->flush();
    }
}
