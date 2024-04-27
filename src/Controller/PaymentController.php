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
use Stripe;
use Stripe\PaymentIntent;

class PaymentController extends AbstractController
{
    #[Route('/payment/{refCommande}', name: 'app_payment')]
    public function index($refCommande): Response
    {
        $pseudo = 'dida';
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);
    
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['iduser' => $utilisateur, 'status' => 'enAttente']);
    
        // Vérifier si la commande existe
        if ($commande) {
            // Récupérer le montant total de la commande
            $total = $commande->getPrix();
    
            // Rendre la vue avec les détails de la commande, y compris le clientSecret
            return $this->render('payment/check.html.twig', [
                'controller_name' => 'PaymentController',
                'refCommande' => $refCommande,
                'commande' => $commande,
                'total' => $total,
                'stripe_key' => $_ENV["STRIPE_KEY"],
            ]);
        } else {
            // Si aucune commande n'est trouvée, vous pouvez renvoyer un message d'erreur ou rediriger l'utilisateur
            // Dans cet exemple, nous redirigeons l'utilisateur vers une autre page
            return $this->redirectToRoute('ClientProd'); // Rediriger l'utilisateur vers la page d'accueil par exemple
        }
    }
    
    






    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
public function createCharge(Request $request)
{
    // Récupérer l'utilisateur actuel
    $pseudo = 'dida';
    $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);

    // Récupérer la commande en cours de l'utilisateur (en supposant qu'il n'y a qu'une seule commande en attente)
    $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy([
        'iduser' => $utilisateur,
        'status' => 'enAttente'
    ]);

    // Vérifier si une commande existe pour cet utilisateur
    if (!$commande) {
        // Gérer l'erreur, par exemple, rediriger l'utilisateur vers une page d'erreur
        return $this->redirectToRoute('error_page');
    }

    // Récupérer le prix de la commande de l'utilisateur
    $montant = $commande->getPrix() * 100; // Convertir le montant en centimes (Stripe exige le montant en plus petite unité monétaire)
    
    // Créer la charge Stripe avec le montant de la commande
    Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
    Stripe\Charge::create([
        "amount" => $montant,
        "currency" => "usd",
        "source" => $request->request->get('stripeToken'),
        "description" => "Binaryboxtuts Payment Test"
    ]);

    // Ajouter un message flash pour indiquer que le paiement a réussi
    $this->addFlash(
        'success',
        'Payment Successful!'
    );
    // Rediriger l'utilisateur vers une page de confirmation de paiement
// Ajouter un message flash pour indiquer que le paiement a réussi
$this->addFlash('success', 'Payment Successful!');

// Rediriger l'utilisateur vers une page de confirmation de paiement
return $this->redirectToRoute('payment_success');}

    









     #[Route('/payment/success', name: 'payment_success')]
     public function paymentSuccess(): Response
     {
        /* $pseudo = 'dida';

         // Créer une nouvelle commande pour l'utilisateur
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);
         $nouvelleCommande = new Commande();
         $nouvelleCommande->setIduser($utilisateur);
         $nouvelleCommande->setPrix(0); // Prix de 0 pour la nouvelle commande
         $nouvelleCommande->setStatus('enAttente');
     
         // Persister la nouvelle commande
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($nouvelleCommande);
         $entityManager->flush(); */
     
         // Rendre la vue de confirmation de commande
         return $this->render('payment/success.html.twig');
     }

      
        


















    }

   








    



















