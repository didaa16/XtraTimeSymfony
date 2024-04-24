<?php

namespace App\Controller;

use App\Repository\ParticipationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participation;


class ParticipationController extends AbstractController
{
    #[Route('/participation', name: 'app_participation')]
    public function index(): Response
    {
        return $this->render('participation/index.html.twig', [
            'controller_name' => 'ParticipationController',
        ]);
    }


     //afficher 
     #[Route('/Affiche_Participation', name: 'Participation_Affiche')]
     public function Affiche(ParticipationRepository $repository): Response
     {
         $participations = $repository->findAll(); // Fetch all Participation
         return $this->render('participation/Afficheparticipation.html.twig', ['p' => $participations]);
     }


     
     //supprimer
#[Route('/participation_delete/{idparticipation}', name: 'participation_delete')]
public function event_delete($idparticipation, ParticipationRepository $repository)
{
    $participation = $repository->find($idparticipation);

    

        if (!$participation) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

    $em = $this->getDoctrine()->getManager();
    $em->remove($participation);
    $em->flush();

    return $this->redirectToRoute('Participation_Affiche');
}

 

//stat 
#[Route('/nbr', name: 'nbr_participation')]
public function nbr(): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Fetch all participations
    $participations = $entityManager->getRepository(Participation::class)->findAll();

    // Initialize an array to hold event participation counts
    $eventParticipationCounts = [];

    // Count participations per event
    foreach ($participations as $participation) {
        $eventName = $participation->getIdevent()->getTitre();

        if (!isset($eventParticipationCounts[$eventName])) {
            $eventParticipationCounts[$eventName] = 0;
        }

        $eventParticipationCounts[$eventName]++;
    }

    return $this->render('participation/stat.html.twig', [
        'eventParticipationCounts' => $eventParticipationCounts,
    ]);
}

}
