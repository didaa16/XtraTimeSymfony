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

class PaymentController extends AbstractController
{
    #[Route('/payment/{refCommande}', name: 'app_payment')]
    public function index($refCommande): Response
    {
        $pseudo = 'dida';
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);

        // Récupérer la commande de l'utilisateur avec la référence fournie
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy([
            'iduser' => $utilisateur,
            'refCommande' => $refCommande,
            'status' => 'enAttente'
        ]);

        // Vérifier si la commande existe
        if ($commande) {
            // Récupérer le montant total de la commande
            $total = $commande->getPrix();

            // Récupérer le client secret pour le paiement Stripe
$clientSecret = $this->getStripeClientSecret($total);

// Rendre la vue avec les détails de la commande, y compris le clientSecret
return $this->render('payment/check.html.twig', [
    'controller_name' => 'PaymentController',
    'refCommande' => $refCommande,
    'commande' => $commande,
    'total' => $total,
    'clientSecret' => $clientSecret // Assurez-vous que cette variable est transmise au modèle Twig
]);
        } else {
            // Si aucune commande n'est trouvée, vous pouvez renvoyer un message d'erreur ou rediriger l'utilisateur
            // Dans cet exemple, nous redirigeons l'utilisateur vers une autre page
            return $this->redirectToRoute('ClientProd'); // Rediriger l'utilisateur vers la page d'accueil par exemple
        }
    }
     // Fonction pour récupérer le client secret de Stripe pour un montant donné
     private function getStripeClientSecret($amount): string
     {
         // Votre clé secrète Stripe
         Stripe::setApiKey('sk_test_51OqJbyP8fZ7mtZrf1ucCZuWgiG9TwaEZeDfFgVTC7dM2lapkIL1Hiq8LJKjLK2YVbdlYTFCHI3FgIthXHJgTxaa800QGib2xV2');
 
         // Créer un nouvel objet PaymentIntent avec le montant
         $paymentIntent = PaymentIntent::create([
             'amount' => $amount * 100, // Le montant doit être en cents
             'currency' => 'eur' // Devrait correspondre à votre devise préférée
         ]);
 
         // Renvoyer le client_secret du PaymentIntent pour l'authentification du paiement côté client
         return $paymentIntent->client_secret;
     }

     #[Route('/payment/success', name: 'payment_success')]
public function paymentSuccess(): Response
{
    // Rendre la vue de confirmation de commande
    return $this->render('payment/success.html.twig');
}

      
        


















    }

   








    



















