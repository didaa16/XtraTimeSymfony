<?php

namespace App\Controller;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Valid;
use App\Entity\Ratingprod; // Ajout de l'importation manquante
use Symfony\Component\HttpFoundation\File\UploadedFile; // Importez UploadedFile



class ProduitController extends AbstractController
{
   

    #[Route('/AjouterProduit', name: 'AjouterProduit')]
    public function AjouterProduit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
    
        // Handle form submission
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();
                
                // Move the file to the directory where images are stored
                $imageFile->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
                
                // Update the 'image' property of the produit entity with the file name
                $produit->setImage($fileName);
            }
            
            // Sauvegarde du produit en base de données
            $entityManager->persist($produit);
            $entityManager->flush();
    
            // Redirection vers une autre page après l'ajout réussi
            return $this->redirectToRoute('ListeProduit');
        }
    
        return $this->render('produit/ajouterProduit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
 


    
    #[Route('/modifierProduit/{ref}', name: 'modifierProduit')]
    public function modifierProduit(Request $request, EntityManagerInterface $entityManager, string $ref): Response
    {
        // Récupérer le produit à modifier depuis la base de données
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($ref);
    
        // Vérifier si le produit existe
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
    
        // Récupérer le nom de l'image actuelle du produit
        $ancienneImage = $produit->getImage();
    
        // Créer le formulaire de modification avec les données du produit récupéré
        $form = $this->createForm(ProduitType::class, $produit);
    
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement de la nouvelle image
            $nouvelleImage = $form->get('image')->getData();
            if ($nouvelleImage) {
                // Générer un nom unique pour le fichier avant de le sauvegarder
                $nomFichier = md5(uniqid()) . '.' . $nouvelleImage->guessExtension();
    
                // Déplacer le fichier vers le répertoire où les images sont stockées
                $nouvelleImage->move(
                    $this->getParameter('image_directory'),
                    $nomFichier
                );
    
                // Mettre à jour la propriété 'image' de l'entité produit avec le nom du fichier
                $produit->setImage($nomFichier);
            } else {
                // Si aucun nouveau fichier image n'a été téléchargé, conserver l'ancienne valeur de l'image
                $produit->setImage($ancienneImage);
            }
    
            // Mettre à jour le produit avec les nouvelles données
            $entityManager->flush();
    
            // Redirection vers une autre page après la modification réussie
            return $this->redirectToRoute('ListeProduit');
        }
    
        return $this->render('produit/modifierProduit.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
            'ancienneImage' => $ancienneImage, // Passer l'ancienne image au formulaire Twig
        ]);
    }
    


    #[Route('/afficherProduit', name: 'ListeProduit')]
    public function AfficheP(ProduitRepository $repository)
    {
        $produit = $repository->findAll(); //select *
        return $this->render('produit/listeProduit.html.twig', ['produits' => $produit]);
    }
    

   
/*
#[Route('/deleteProduit/{ref}', name: 'deleteProduit')]
public function deleteProduit(Request $request, ProduitRepository $repository, EntityManagerInterface $entityManager)
{  
    // Récupérer la référence du produit depuis la requête
    $ref = $request->query->get('ref');

    // Recherche du produit par sa référence
    $produit = $repository->findOneBy(['ref' => $ref]);

    // Vérifier si le produit existe
    if (!$produit) {
        throw $this->createNotFoundException('Produit non trouvé');
    }

    // Supprimer le produit
    $entityManager->remove($produit);
    $entityManager->flush();

    // Rediriger vers la liste des produits après la suppression
    return $this->redirectToRoute('ListeProduit');
}
*/
#[Route('/deleteProduit/{ref}', name: 'deleteProduit')]
public function supprimer(Request $request, $ref): Response
{
    // Récupérer le gestionnaire d'entités
    $entityManager = $this->getDoctrine()->getManager();

    // Récupérer le produit à supprimer
    $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['ref' => $ref]);

    // Vérifier si le produit existe
    if (!$produit) {
        throw $this->createNotFoundException('Le produit n\'existe pas.');
    }

    // Récupérer les enregistrements liés dans la table ratingprod par l'ID du produit
    $ratings = $entityManager->getRepository(Ratingprod::class)->findBy(['ref' => $produit]);

    // Supprimer les enregistrements liés dans la table ratingprod
    foreach ($ratings as $rating) {
        $entityManager->remove($rating);
    }

    // Supprimer le produit
    $entityManager->remove($produit);
    $entityManager->flush();

    // Afficher un message de confirmation
    $this->addFlash('success', 'Le produit et les évaluations associées ont été supprimés avec succès.');

    // Rediriger vers une autre page après la suppression
    return $this->redirectToRoute('ListeProduit');
}


/*
#modifier
#[Route('/modifierProduit/{ref}', name: 'modifierProduit')]
public function modifierProduit(Request $request, EntityManagerInterface $entityManager, string $ref): Response
{
  // Récupérer le produit à éditer depuis la base de données
  $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['ref' => $ref]);

  if (!$produit) {
    throw $this->createNotFoundException('Produit non trouvé');
  }

  // Créer le formulaire d'édition avec le produit récupéré
  $form = $this->createForm(ProduitType::class, $produit, [
    'data_class' => Produit::class,
  ]);
  $form->handleRequest($request);

  if ($form->isSubmitted() && $form->isValid()) {
    // Gérer la soumission du formulaire
/*
    // Récupérer le fichier de l'image
    $imageFile = $form->get('image')->getData();

    // Vérifier si un nouveau fichier image a été téléchargé
    if ($imageFile instanceof UploadedFile) {
      // Générer un nom de fichier unique
      $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();

      // Déplacer le fichier téléchargé vers le répertoire des images
      $imageFile->move($this->getParameter('image_directory'), $fileName);

      // Mettre à jour le champ d'image de l'entité Produit avec le nom du fichier
      $produit->setImage($fileName);
    }

    // Enregistrer les modifications du produit
    $entityManager->flush();

    // Rediriger après la modification réussie
    return $this->redirectToRoute('ListeProduit');
  }

  return $this->render('produit/modifierProduit.html.twig', [
    'form' => $form->createView(),
    'produit' => $produit,
  ]);
}
*/



}



