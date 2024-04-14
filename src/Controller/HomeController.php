<?php

namespace App\Controller;

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
    public function back(): Response
    {
        return $this->render('home/back.html.twig' ,[
            'title' => 'welcome',
        ]);
    }
}
