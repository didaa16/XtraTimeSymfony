<?php

namespace App\Controller;

use App\Entity\Sponso;
use App\Form\SponsoType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SponsoRepository; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile; // Import UploadedFile
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SponsoController extends AbstractController
{
    #[Route('/sponso', name: 'app_sponso')]
    public function index(): Response
    {
        return $this->render('sponso/index.html.twig', [
            'controller_name' => 'SponsoController',
        ]);
    }


     //afficher 
     #[Route('/Affiche_sponso', name: 'sponso_Affiche')]
     public function Affiche(SponsoRepository $repository): Response
     {
         $sponsos = $repository->findAll(); // Fetch all events
         return $this->render('sponso/Affichesponso.html.twig', ['s' => $sponsos]);
     }

}
