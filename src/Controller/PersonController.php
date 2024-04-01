<?php

namespace App\Controller;

use App\Repository\PersonRepository;
use App\Service\PersonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use \DateTimeImmutable;

class PersonController extends AbstractController
{
    #[Route('/api/people', name: 'save_person', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['firstname']) || !isset($data['lastname']) || !isset($data['birthdate'])) {
            return new Response('Les champs nom, prénom et date de naissance sont obligatoires', Response::HTTP_BAD_REQUEST);
        }

        $birthdate = \DateTime::createFromFormat(Person::FORMAT_BIRTHDATE, $data['birthdate']);
        
        if (!$birthdate) {
            return new Response('La date de naissance doit être au format YYYY-MM-DD', Response::HTTP_BAD_REQUEST);
        }

        if ($birthdate->diff(new \DateTime())->y >= Person::MAX_AGE) {
            return new Response('L\'âge maximum autorisé est de 150 ans', Response::HTTP_BAD_REQUEST);
        }

        $person = new Person();
        $person->setFirstname($data['firstname']);
        $person->setLastname($data['lastname']);
        $person->setBirthdate(DateTimeImmutable::createFromMutable($birthdate));

        $entityManager->persist($person);
        $entityManager->flush();

        return new Response('Personne enregistrée avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/people', name: 'get_people', methods: ['GET'])]
    public function getAll(
        PersonRepository $personRepository,
        PersonService $personService,
        Request $request, 
        EntityManagerInterface $entityManager): Response
    {
        $people = $personRepository->findBy([], ['lastname' => 'ASC', 'firstname' => 'ASC']);

        foreach ($people as $person) {
            $age = $personService->calculateAge($person->getBirthdate());

            $personDatas = [
                'id'       => $person->getId(),
                'lastname' => $person->getLastname(),
                'prenom'   => $person->getFirstname(),
                'age'      => $age,
                'jobs'     => $person->getJobs(),
            ];

            $datas[] = $personDatas;
        }

        return $this->json($datas);
    }
}
