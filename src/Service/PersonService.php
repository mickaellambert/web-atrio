<?php

namespace App\Service;

use App\Entity\Person;
use DateTimeImmutable;
use DateTime;

class PersonService
{
    public function calculateAge(DateTimeImmutable $birthdate): int
    {
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthdate)->y;

        return $age;
    }
}