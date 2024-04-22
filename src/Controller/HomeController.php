<?php

namespace App\Controller;

use App\Repository\UtilisateursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/', name: 'app_front')]
    public function front(): Response
    {
        return $this->render('home/front.html.twig' ,[
            'title' => 'welcome',
        ]);
    }


    #[Route('/back', name: 'app_back')]
    public function back(UtilisateursRepository $userRepository): Response
    {
        // 1. Extraire les données de la base de données
        $usersByRole = $userRepository->countUsersByRole();

        // 2. Préparer les données pour le graphique
        $labels = [];
        $data = [];
        $totalUsers = 0;

        // Calculer le nombre total d'utilisateurs
        foreach ($usersByRole as $roleData) {
            $totalUsers += $roleData['count'];
        }

        foreach ($usersByRole as $roleData) {
            $role = $roleData['roles'];
            $count = $roleData['count'];
            $labels[] = $role;
            $percentage = ($count / $totalUsers) * 100;
            $data[] = round($percentage, 2);
        }


        // 3. Afficher le graphique
        return $this->render('home/back.html.twig', [
            'labels' => json_encode($labels),
            'data' => json_encode($data),
        ]);
    }

    #[Route('/banned', name: 'app_banned')]
    public function banned(): Response
    {
        return $this->render('home/banned.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
