<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Person;
use Faker\Factory;
use \DateTimeImmutable;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++) {
            $person = new Person();
            $person->setFirstname($faker->firstName());
            $person->setLastname($faker->lastName());
            $person->setBirthdate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-80 years', '-18 years')));

            $manager->persist($person);

            $this->addReference('person_' . $i, $person); 
        }

        $manager->flush();
    }
}
