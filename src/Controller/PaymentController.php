<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Component\Validator\Constraints\Valid;
use App\Entity\Ratingprod; // Ajout de l'importation manquante
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Entity\Utilisateurs;
use App\Repository\ProduitCommandeRepository;

class PaymentController extends AbstractController
{
    #[Route('/payment/{refCommande}', name: 'app_payment')]
    public function index($refCommande): Response
    {
        $pseudo = 'dida';
    
        // Récupérer l'utilisateur en utilisant son pseudo
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);
    
        // Récupérer la commande de l'utilisateur avec la référence fournie
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['iduser' => $utilisateur, 'refCommande' => $refCommande, 'status' => 'enAttente']);
    
        // Vérifier si la commande existe
        if ($commande) {
            // Rendre la vue avec les détails de la commande et le prix total
            return $this->render('payment/check.html.twig', [
                'controller_name' => 'PaymentController',
                'refCommande' => $refCommande,
                'commande' => $commande,
                'total' => $commande->getPrix(), // Récupérer directement le prix de la commande depuis l'entité Commande
            ]);
            
        } else {
            // Si aucune commande n'est trouvée, vous pouvez renvoyer un message d'erreur ou rediriger l'utilisateur
            // Dans cet exemple, nous redirigeons l'utilisateur vers une autre page
            return $this->redirectToRoute('ClientProd'); // Rediriger l'utilisateur vers la page d'accueil par exemple
        }
    }

    #[Route('/process-payment', name: 'process_payment', methods: ['POST'])]
    public function checkoutPage(Request $request)
{
    // Récupérer les données soumises du formulaire
    $firstName = $request->request->get('fname');
    $lastName = $request->request->get('lname');
    $address = $request->request->get('address-1');
    $city = $request->request->get('town-city');
    $email = $request->request->get('email');
    $phone = $request->request->get('phone');

    // Traiter les données ici, par exemple les enregistrer dans des variables locales

    // Passer les données au modèle Twig pour les afficher si nécessaire
    return $this->render('votre_vue.twig', [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'address' => $address,
        'city' => $city,
        'email' => $email,
        'phone' => $phone,
        //'total' => // votre logique pour calculer le total de la commande ici
    ]);
}



#[Route('/payer/{refCommande}', name: 'payer')]
public function payer($refCommande): Response
{
    $pseudo = 'dida';

    // Récupérer l'utilisateur en utilisant son pseudo
    $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);

    // Récupérer la commande de l'utilisateur avec la référence fournie
    $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['iduser' => $utilisateur, 'refCommande' => $refCommande, 'status' => 'enAttente']);

    // Vérifier si la commande existe
    if ($commande) {
        // Rendre la vue avec les détails de la commande et le prix total
        return $this->render('payment/pay.html.twig', [
            'controller_name' => 'PaymentController',
            'refCommande' => $refCommande,
            'commande' => $commande,
            'total' => $commande->getPrix(), // Récupérer directement le prix de la commande depuis l'entité Commande
        ]);
        
    } else {
        // Si aucune commande n'est trouvée, vous pouvez renvoyer un message d'erreur ou rediriger l'utilisateur
        // Dans cet exemple, nous redirigeons l'utilisateur vers une autre page
        return $this->redirectToRoute('app_payment'); // Rediriger l'utilisateur vers la page d'accueil par exemple
    }
}


















}