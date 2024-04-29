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
use App\Entity\Ratingprod; 
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Entity\Utilisateurs;
use App\Repository\ProduitCommandeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;




class ClientProdController extends AbstractController
{
    
    #[Route('/ClientProd', name: 'ClientProd')]
    public function ClientProd(Request $request, ProduitRepository $repository, PaginatorInterface $paginator)
    {
        // Récupération de l'option de tri de la requête ou utilisation de la valeur par défaut
        $sortBy = $request->query->get('sort_by', 'default');

        // Récupération des produits triés
        $produits = $this->getProduitsTries($sortBy, $repository);
        // Récupération de tous les produits
    $produitsQuery = $repository->findAll();

 // Paginer les résultats
$produits = $paginator->paginate(
    $produits, // Utilisation des produits triés pour la pagination
    $request->query->getInt('page', 1), // Numéro de page à afficher
    6 // Nombre d'éléments par page
);
        // Rendu de la vue avec les données des produits
        return $this->render('Client_prod/shop.html.twig', [
            'produits' => $produits,
        ]);
    }

    
    // Fonction pour récupérer les produits triés en fonction de l'option sélectionnée
    private function getProduitsTries($sortBy, $repository)
    {
        switch ($sortBy) {
            case 'price_asc':
                return $repository->findBy([], ['prix' => 'ASC']);
            case 'price_desc':
                return $repository->findBy([], ['prix' => 'DESC']);
            case 'name_asc':
                return $repository->findBy([], ['nom' => 'ASC']);
            // Par défaut, retourner tous les produits
            default:
                return $repository->findAll();
        }

    }

private function filterProductsByPrice($minPrice, $maxPrice)
    {
        // Récupérer les produits dans la plage de prix spécifiée
        $filteredProducts = $this->getDoctrine()->getRepository(Produit::class)
            ->findByPriceRange($minPrice, $maxPrice);

        return $filteredProducts;
    }



/* details */

#[Route('/MoreDetailsShop/{ref}', name: 'MoreDetailsShop')]
    public function MoreDetailsShop($ref): Response
    {
        // Retrieve the product from the database based on the identifier
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($ref);

        if (!$produit) {
            throw $this->createNotFoundException('Product not found');
        }

        // Example: Fetching related products based on the type of sport
        $relatedProducts = $this->getDoctrine()->getRepository(Produit::class)->findBy(['typesport' => $produit->getTypesport()]);

        // Calculate the average rating for the product
        $averageRating = $this->calculateAverageRating($ref);
        $ratingCount = $this->getRatingCountForProduct($ref);


        // Render the view with the product details
        return $this->render('client_prod/single-shop.html.twig', [
            'produit' => $produit,
            'relatedProducts' => $relatedProducts,
            'averageRating' => $averageRating,
            'ratingCount' => $ratingCount,
        ]);
    }

    // Fonction calcul average
    private function calculateAverageRating(string $ref): float
    {
    // Récupérer les évaluations du produit à partir de la base de données
    $ratings = $this->getDoctrine()->getRepository(Ratingprod::class)->findBy(['ref' => $ref]);

    // Initialiser la somme des notes et le nombre d'évaluations
    $totalRating = 0;
    $totalRatingsCount = count($ratings);

    // Calculer la somme des notes
    foreach ($ratings as $rating) {
        $totalRating += $rating->getRating();
    }

    // Calculer la note moyenne (éviter la division par zéro)
    $averageRating = $totalRatingsCount > 0 ? round($totalRating / $totalRatingsCount, 1) : 0;

    return $averageRating;
    }



// Méthode pour récupérer le nombre de ratings pour un produit spécifique
private function getRatingCountForProduct(string $ref): int
{
    $entityManager = $this->getDoctrine()->getManager();

    // Utilisez votre propre méthode pour récupérer les ratings associés au produit
    // Supposons que vous ayez une entité Rating avec une propriété productRef qui stocke la référence du produit
    $ratings = $this->getDoctrine()->getRepository(Ratingprod::class)->findBy(['ref' => $ref]);

    // Compter le nombre de ratings
    $ratingCount = count($ratings);

    return $ratingCount;
}







    #[Route('/AddCart/{ref}', name: 'AddCart')]
    public function addToCart(Request $request, string $ref): Response
    {
        // Récupérez l'utilisateur actuel (fixe pour l'exemple)
        $pseudo = 'dida';
    
        // Vérifiez si le produit existe dans la base de données
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['ref' => $ref]);
    
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
    
        // Récupérer l'utilisateur en utilisant son pseudo
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);
    
        // Récupérer la commande en attente de l'utilisateur
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['iduser' => $utilisateur, 'status' => 'enAttente']);
    
        if (!$commande) {
            // Si aucune commande en attente n'existe, créez une nouvelle commande
            $commande = new Commande();
            $commande->setIduser($utilisateur);
            $commande->setStatus('enAttente');
            $commande->setPrix(0);
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();
        }
    
        // Ajouter le produit à la commande de l'utilisateur
        $produitCommande = new ProduitCommande();
        $produitCommande->setRef($produit->getRef());
        $produitCommande->setRefCommande($commande->getRefCommande());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produitCommande);
        $entityManager->flush();
        // Ajouter un message flash pour indiquer que le produit a été ajouté avec succès
    
        // Rediriger l'utilisateur vers la page shop après l'ajout au panier
        return $this->redirectToRoute('ClientProd'); 
    }
    
    
    
 

    #[Route('/myOrder', name: 'my_order')]
    public function myOrder(): Response
    {
        // Récupérer l'utilisateur actuel (fixe pour l'exemple)
        $pseudo = 'dida';
    
        // Récupérer l'utilisateur en utilisant son pseudo
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);
    
        // Récupérer la commande de l'utilisateur
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['iduser' => $utilisateur, 'status' => 'enAttente']);
    
        // Vérifier si la commande existe
        if ($commande) {
            // Récupérer les produits associés à cette commande
            $produitsCommande = $this->getDoctrine()->getRepository(ProduitCommande::class)->findBy(['refCommande' => $commande->getRefCommande()]);
    
            // Créer un tableau pour stocker le nombre de produits associés à chaque produit
            $produitsQuantite = [];
    
            // Compter le nombre de produits associés à chaque produit dans la commande
            foreach ($produitsCommande as $produitCommande) {
                $refProduit = $produitCommande->getRef();
                if (!isset($produitsQuantite[$refProduit])) {
                    $produitsQuantite[$refProduit] = 0;
                }
                $produitsQuantite[$refProduit]++;
            }
    
            // Récupérer les détails de chaque produit associé à la commande
            $produits = [];
            foreach ($produitsQuantite as $refProduit => $quantite) {
                $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['ref' => $refProduit]);
                if ($produit) {
                    $produitDetails = [
                        'produit' => $produit,
                        'quantite' => $quantite
                    ];
                    $produits[] = $produitDetails;
                }


                # Récupérer la référence de la commande
                $refCommande = $produitCommande->getRefCommande();

                # Récupérer tous les produits associés à cette commande
                $produitsCommande = $this->getDoctrine()->getRepository(ProduitCommande::class)->findBy(['refCommande' => $refCommande]);

                # Initialiser la somme des prix des produits
                $sousTotal = 0;

               # Parcourir les produits associés à cette commande
               foreach ($produitsCommande as $produitCommande) {
               // Récupérer la référence du produit depuis ProduitCommande
               $refProduit = $produitCommande->getRef();
    
               // Récupérer le prix du produit en utilisant sa référence
               $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['ref' => $refProduit]);
    
    // Vérifier si le produit existe
    if ($produit) {
        // Ajouter le prix du produit au sous-total
        $sousTotal += $produit->getPrix();
    }
}

# Mettre à jour l'attribut prix de la commande avec le sous-total calculé
$commande->setPrix($sousTotal);

# Enregistrer les modifications dans la base de données
$entityManager = $this->getDoctrine()->getManager();
$entityManager->persist($commande);
$entityManager->flush();





            }
    
            // Rendre la vue avec les détails de la commande
            return $this->render('Client_prod/cart.html.twig', [
                'commande' => $commande,
                'produits' => $produits
            ]);
        } else {
            // Si aucune commande n'est trouvée, vous pouvez renvoyer un message ou rediriger l'utilisateur
            // Dans cet exemple, nous redirigeons l'utilisateur vers une autre page
            return $this->redirectToRoute('ClientProd'); // Rediriger l'utilisateur vers la page d'accueil par exemple
        }
    }
    
    
   /*  -------------------supprimmer------------- */


   
   #[Route('/RemoveFromCart/{ref}', name: 'RemoveFromCart')]
   public function removeFromCart(Request $request, string $ref): Response
   {
       // Récupérez l'utilisateur actuel (fixe pour l'exemple)
       $pseudo = 'dida';
   
       // Récupérez l'utilisateur en utilisant son pseudo
       $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);
   
       // Récupérez la commande en cours de l'utilisateur
       $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['iduser' => $utilisateur, 'status' => 'enAttente']);
   
       // Vérifiez si la commande existe
       if (!$commande) {
           throw $this->createNotFoundException('Commande en cours non trouvée');
       }
   
       // Récupérez le produit commandé à supprimer de la base de données en utilisant à la fois la référence du produit et la référence de la commande
       $produitCommande = $this->getDoctrine()->getRepository(ProduitCommande::class)->findOneBy(['ref' => $ref, 'refCommande' => $commande->getRefCommande()]);
   
       if (!$produitCommande) {
           throw $this->createNotFoundException('Produit commandé non trouvé');
       }
   
       // Supprimez le produit de la commande
       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->remove($produitCommande);
       $entityManager->flush();
   
       // Recalculer le prix total de la commande
       $produitsCommande = $this->getDoctrine()->getRepository(ProduitCommande::class)->findBy(['refCommande' => $commande->getRefCommande()]);
       $sousTotal = 0;
       foreach ($produitsCommande as $produitCommande) {
           $refProduit = $produitCommande->getRef();
           $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['ref' => $refProduit]);
           if ($produit) {
               $sousTotal += $produit->getPrix();
           }
       }
   
       // Mettre à jour le prix de la commande
       $commande->setPrix($sousTotal);
       $entityManager->persist($commande);
       $entityManager->flush();
   
       // Rediriger l'utilisateur vers la page de commande après la suppression
       return $this->redirectToRoute('my_order');
   }
   




#[Route('/update-cart', name: 'update_cart')]
public function updateCart(Request $request): Response
{
    // Récupérer les données envoyées depuis le client
    $data = json_decode($request->getContent(), true);
    $productId = $data['productId'];
    $newQuantity = $data['newQuantity'];
    
    // Récupérer l'utilisateur actuel (fixe pour l'exemple)
    $pseudo = 'dida';

    // Récupérer l'utilisateur en utilisant son pseudo
    $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);

    // Récupérer la commande de l'utilisateur
    $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['iduser' => $utilisateur, 'status' => 'enAttente']);

    if (!$commande) {
        throw $this->createNotFoundException('Commande en cours non trouvée');
    }

    // Récupérer le produit commandé à mettre à jour de la base de données
    $produitCommande = $this->getDoctrine()->getRepository(ProduitCommande::class)->findOneBy(['ref' => $productId, 'refCommande' => $commande->getRefCommande()]);

    if (!$produitCommande) {
        throw $this->createNotFoundException('Produit commandé non trouvé');
    }

    // Mettre à jour la quantité du produit dans le panier
    $produitCommande->setQuantite($newQuantity);

    // Enregistrer les modifications dans la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($produitCommande);
    $entityManager->flush();

    // Envoyer une réponse JSON indiquant que la mise à jour du panier s'est bien déroulée
    return new JsonResponse(['message' => 'Mise à jour du panier réussie'], Response::HTTP_OK);
}

/* rating */


#[Route('/rate-product/{ref}', name: 'rate_product')]
public function rateProduct(Request $request, string $ref): Response
{
    // Récupérez la note de la requête
    $ratingValue = $request->request->get('rating');

   
    $pseudo = 'dida';

    // Récupérez l'utilisateur en utilisant son pseudo
    $user = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);

    if (!$user) {
        // Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté
        // Remplacez 'login_route' par le nom de votre route de connexion
        return $this->redirectToRoute('login_route');
    }

    // Récupérez le produit à noter
    $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['ref' => $ref]);

    // Vérifiez si le produit existe
    if (!$produit) {
        throw $this->createNotFoundException('Produit non trouvé');
    }

    // Vérifiez si l'utilisateur a déjà noté ce produit
    $rating = $this->getDoctrine()->getRepository(Ratingprod::class)->findOneBy(['ref' => $produit, 'iduser' => $user]);
    if ($rating) {
        // Mettez à jour la note existante
        $rating->setRating($ratingValue);
    } else {
        // Créez une nouvelle instance de Ratingprod
        $rating = new Ratingprod();
        $rating->setRating($ratingValue);
        $rating->setIduser($user);
        $rating->setRef($produit);
    }
   
    // Récupérez l'EntityManager
    $entityManager = $this->getDoctrine()->getManager();

    // Persistez la note
    $entityManager->persist($rating);

    // Enregistrez les modifications dans la base de données
    $entityManager->flush();
     // Récupérez toutes les notes pour le produit
     $ratings = $this->getDoctrine()->getRepository(Ratingprod::class)->findBy(['ref' => $produit]);

    
 

    // Redirigez l'utilisateur vers la page de détails du produit après la notation
    return $this->redirectToRoute('MoreDetailsShop', ['ref' => $ref]);
}













}