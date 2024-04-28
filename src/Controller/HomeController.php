<?php

namespace App\Controller;
use App\Repository\AbonnementRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function front(): Response
    {
        return $this->render('/front.html.twig' ,[
            'title' => 'welcome',
        ]);
    }


    #[Route('/back', name: 'app_back')]
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        // Récupérer tous les abonnements depuis la base de données
        $abonnements = $abonnementRepository->findAll();
    
        // Initialiser un tableau pour stocker les occurrences par pack
        $occurrencesParPack = [];
    
        // Calculer les occurrences par pack
        foreach ($abonnements as $abonnement) {
            $nomPack = $abonnement->getNomPack();
            if (!isset($occurrencesParPack[$nomPack])) {
                $occurrencesParPack[$nomPack] = 1;
            } else {
                $occurrencesParPack[$nomPack]++;
            }
        }
    
        // Trier les occurrences par ordre décroissant
        arsort($occurrencesParPack);
    
        // Limiter le nombre de packs à afficher dans le diagramme (par exemple, les 5 premiers packs les plus utilisés)
        $occurrencesParPack = array_slice($occurrencesParPack, 0, 5, true);
    
        // Calculer le total des occurrences pour obtenir le pourcentage
        $totalOccurrences = array_sum($occurrencesParPack);
    
        // Initialiser un tableau pour stocker les pourcentages par pack
        $pourcentagesParPack = [];
    
        // Calculer les pourcentages par pack
        foreach ($occurrencesParPack as $nomPack => $occurrences) {
            $pourcentage = ($occurrences / $totalOccurrences) * 100;
            $pourcentagesParPack[$nomPack] = $pourcentage;
        }
    
        return $this->render('back.html.twig', [
            'pourcentagesParPack' => $pourcentagesParPack,
        ]);
    }
    


    
    
    
    private function getRandomColor(): string
    {
        $letters = '0123456789ABCDEF';
        $color = '#';
        for ($i = 0; $i < 6; $i++) {
            $color .= $letters[rand(0, 15)];
        }
        return $color;
    }
    
}
