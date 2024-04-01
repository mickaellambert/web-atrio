<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Repository\PersonRepository;
use App\Repository\WorkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Person;
use App\Entity\Work;

use Doctrine\ORM\EntityManagerInterface;
use \DateTimeImmutable;

class JobController extends AbstractController
{
    #[Route('/api/job/person/{personId}/work/{workId}', name: 'save_job', methods: ['POST'])]
    public function save(
        int $personId, 
        int $workId, 
        Request $request, 
        PersonRepository $personRepository,
        WorkRepository $workRepository,
        JobRepository $jobRepository,
        EntityManagerInterface $entityManager): Response
    {
        // Je n'ai pas réussi à faire fonctionner les paramConverter afin de récupérer directement les objets
        // Je ne voulais pas passer trop de temps à debuger, je suis donc passé à la suite

        $person = $personRepository->find($personId);

        if (!$person) {
            return new Response('Personne non trouvée', Response::HTTP_NOT_FOUND);
        } 

        $work = $workRepository->find($workId);

        if (!$work) {
            return new Response('Work non trouvé', Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['began_at'])) {
            return new Response('La date de début est obligatoire', Response::HTTP_BAD_REQUEST);
        }

        $beganAt = \DateTime::createFromFormat('Y-m-d', $data['began_at']);

        if (!$beganAt) {
            return new Response('La date de début doit être au format YYYY-MM-DD', Response::HTTP_BAD_REQUEST);
        }


        $job = $jobRepository->findOneBy(['person' => $person, 'work' => $work]);

        // Plus de contrôles peuvent être ajoutés ici, mais j'ai peur de manquer de temps
        if ($job && !$job->getEndedAt()) {
            return new Response('L\'emploi que vous essayez d\'ajouter est celui en cours pour la personne désirée', Response::HTTP_BAD_REQUEST);
        }

        $endedAt = null;

        if (isset($data['ended_at'])) {
            $endedAt = DateTimeImmutable::createFromMutable(\DateTime::createFromFormat('Y-m-d', $data['ended_at']));
            
            if (!$endedAt) {
                return new Response('La date de fin doit être au format YYYY-MM-DD', Response::HTTP_BAD_REQUEST);
            }
        }

        $job = new Job();
        $job->setPerson($person);
        $job->setWork($work);
        $job->setBeganAt(DateTimeImmutable::createFromMutable($beganAt));
        $job->setEndedAt($endedAt);

        $entityManager->persist($job);
        $entityManager->flush();

        return new Response('Emploi ajouté à la personne avec succès', Response::HTTP_CREATED);

    }
}
