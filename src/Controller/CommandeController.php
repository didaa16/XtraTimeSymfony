<?php

namespace App\Controller;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;


use App\Entity\Commande;
class CommandeController extends AbstractController
{
    #[Route('/ListeCommande', name: 'ListeCommande')]
    public function AfficherCommande(CommandeRepository $repository,PaginatorInterface $paginator)
    {
        $commande = $repository->findAll(); //select *
         // Récupérer le nombre de commandes pour chaque statut
    $nbCommandesEnAttente = $this->getDoctrine()->getRepository(Commande::class)->count(['status' => 'enAttente']);
    $nbCommandesEnCours = $this->getDoctrine()->getRepository(Commande::class)->count(['status' => 'enCours']);
    $nbCommandesLivre = $this->getDoctrine()->getRepository(Commande::class)->count(['status' => 'livrée']);
    

        return $this->render('commande/listeCommande.html.twig',   [
            'nbCommandesEnAttente' => $nbCommandesEnAttente,
        'nbCommandesEnCours' => $nbCommandesEnCours,
        'nbCommandesLivre' => $nbCommandesLivre,
            'commandes' => $commande]);
    }

    

   /*  $commandesQuery = $repository->findAll(); // Assurez-vous que votre méthode findAllQuery existe dans votre repository

    // Paginer les résultats
$commandes = $paginator->paginate(
    $commandesQuery, // Requête de récupération des commandes
    $request->query->getInt('page', 1), // Numéro de page à afficher
    5 // Nombre d'éléments par page
); */









   


    #[Route('/deleteCommande/{refCommande}', name: 'deleteCommande')]
    public function deleteCommande(Request $request, $refCommande): Response
    {
        // Récupérer le gestionnaire d'entités
        $entityManager = $this->getDoctrine()->getManager();
    
        // Récupérer la commande à supprimer
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['refCommande' => $refCommande]);
    
        // Vérifier si la commande existe
        if (!$commande) {
            throw $this->createNotFoundException('La commande n\'existe pas.');
        }
    
        // Supprimer la commande
        $entityManager->remove($commande);
        $entityManager->flush();
    
        // Afficher un message de confirmation
        $this->addFlash('success', 'La commande et les évaluations associées ont été supprimées avec succès.');
    
        // Rediriger vers une autre page après la suppression
        return $this->redirectToRoute('ListeCommande');
    }






    
}
