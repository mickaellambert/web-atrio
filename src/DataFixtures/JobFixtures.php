<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Job;
use Faker\Factory;
use \DateTimeImmutable;

class JobFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++) {
            $person = $this->getReference('person_' . $i);

            $nbWorks = rand(1, 5);

            for ($j = 1; $j <= $nbWorks; $j++) {
                $work = $this->getReference('work_' . $j);

                $job = new Job();
                $job->setPerson($person);
                $job->setWork($work);

                $beganAt = $faker->dateTimeBetween('-5 years', 'now');
                $job->setBeganAt(DateTimeImmutable::createFromMutable($beganAt));
            
                if ($j !== $nbWorks) {
                    $job->setEndedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween($beganAt, 'now')));
                }

                $manager->persist($job);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PersonFixtures::class,
            WorkFixtures::class,
        ];
    }
}
