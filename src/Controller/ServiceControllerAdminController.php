<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceControllerAdminController extends AbstractController
{
    #[Route('/Admin', name: 'home')]
    public function index(): Response
    {
        return $this->render('back.html.twig', [
            'controller_name' => 'Controller',
        ]);
    }

    #[Route('/AjouterPack', name: 'AjouterPack')]
    public function AjouterPack(): Response
    {
        return $this->render('/Admin/forms-elementsPack.html.twig', [
          
        ]);
    }

    #[Route('/ListePack', name: 'ListePack')]
    public function ListePack(): Response
    {
        return $this->render('/Admin/tables-listjsPack.html.twig', [
          
        ]);
    }

    #[Route('/ListeAbonnement', name: 'ListeAbonnement')]
    public function ListeAbonnement(): Response
    {
        return $this->render('/Admin/tables-listjsAbonnement.html.twig', [
          
        ]);
    }
}
