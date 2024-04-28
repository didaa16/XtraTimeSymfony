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
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Charge;



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
    public function createCharge(Request $request): Response
    {
        // Retrieve the current user
        $pseudo = 'dida';
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);

        // Retrieve the user's pending order (assuming there's only one pending order)
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy([
            'iduser' => $utilisateur,
            'status' => 'enAttente'
        ]);

        // Check if there's a pending order for this user
        if (!$commande) {
            // Handle the error, for example, redirect the user to an error page
            return $this->redirectToRoute('error_page');
        }

        // Retrieve the price of the user's order
        $montantTND = $commande->getPrix();

        // Taux de change TND en USD
        $tauxDeChange = 0.36;
        
        // Convertir le montant en TND en USD
        $montantUSD = $montantTND * $tauxDeChange;
        
        // Convertir le montant en cents (Stripe requiert le montant en plus petite unité de la devise, donc USD * 100)
        $montantEnCents = $montantUSD * 100;
        // Create a Stripe charge with the order amount
        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Charge::create([
            "amount" => $montantEnCents,
            "currency" => "usd",
            "source" => $request->request->get('stripeToken'),
            "description" => "Binaryboxtuts Payment Test"
        ]);

// Set the status of the current order to 'encours'
$commande->setStatus('encours');

// Save the changes to the database
$entityManager = $this->getDoctrine()->getManager();
$entityManager->persist($commande);
$entityManager->flush();

// Create a new empty order for the user
$newCommande = new Commande();
$newCommande->setIdUser($utilisateur); // Associate the order with the current user
$newCommande->setStatus('enAttente'); // Set the status of the new order as pending
$newCommande->setPrix(0); // Set the status of the new order as pending

// You can also set other properties of the order if needed

// Save the new order to the database
$entityManager->persist($newCommande);
$entityManager->flush();


      
// Add a flash message to indicate successful payment
$this->addFlash('success', 'Votre paiement a été effectué avec succès.');

        // Redirect the user to a payment success page
        return $this->redirectToRoute('payment_success');
    }

    #[Route('/payment/success', name: 'payment_success')]
    public function paymentSuccess(): Response
    {
        // Render the order confirmation view
        return $this->render('payment/success.html.twig');
    }





    }

   








    



















