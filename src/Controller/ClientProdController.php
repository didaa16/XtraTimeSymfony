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




class ClientProdController extends AbstractController
{
    
    #[Route('/ClientProd', name: 'ClientProd')]
    public function ClientProd(Request $request, ProduitRepository $repository)
    {
        // Récupération de l'option de tri de la requête ou utilisation de la valeur par défaut
        $sortBy = $request->query->get('sort_by', 'default');

        // Récupération des produits triés
        $produits = $this->getProduitsTries($sortBy, $repository);

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





    #[Route('/MoreDetailsShop/{ref}', name: 'MoreDetailsShop')]
    public function MoreDetailsShop($ref): Response
    {
        // Récupérer le produit depuis la base de données en fonction de l'identifiant
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($ref);
        // Exemple de récupération des produits associés basés sur le type de sport



        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        $relatedProducts = $this->getDoctrine()->getRepository(Produit::class)->findBy(['typesport' => $produit->getTypesport()]);

        // Rendre la vue avec les détails du produit
        return $this->render('client_prod/single-shop.html.twig', [
            'produit' => $produit,
            'relatedProducts' => $relatedProducts,

        ]);
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

    // Récupérez le produit commandé à supprimer de la base de données
    $produitCommande = $this->getDoctrine()->getRepository(ProduitCommande::class)->findOneBy(['ref' => $ref]);

    if (!$produitCommande) {
        throw $this->createNotFoundException('Produit commandé non trouvé');
    }

    // Récupérez l'utilisateur en utilisant son pseudo
    $utilisateur = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['pseudo' => $pseudo]);

    // Récupérer la commande associée au produit commandé
    $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['iduser' => $utilisateur, 'status' => 'enAttente']);

    if (!$commande) {
        throw $this->createNotFoundException('Commande en cours non trouvée');
    }

    // Supprimer le produit de la commande
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










    



}