<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function front(): Response
    {
        return $this->render('front.html.twig' ,[
            'title' => 'welcome',
        ]);
    }


    #[Route('/back', name: 'app_back')]
    public function index(): Response
    {
        return $this->render('back.html.twig' ,[
            'title' => 'welcome',
        ]);
    }

    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return $this->render('test.html.twig' ,[
            'title' => 'welcome',
        ]);
    }
}
