<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Entity\Produit;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use TCPDF;




class LivreurController extends AbstractController
{
    #[Route('/Livreur', name: 'Livreur')]
    public function AfficherCommandeL(CommandeRepository $repository)
    {
        $commande = $repository->findAll(); //select *
        return $this->render('livreur/livreur.html.twig', ['commandes' => $commande]);
    }







    #[Route('/commandeDetL/{refCommande}', name: 'commandeDetL')]
    public function commandeDetL($refCommande): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        // Sélectionner la commande par sa référence (refCommande)
        $commande = $entityManager->getRepository(Commande::class)->findOneBy(['refCommande' => $refCommande]);
        
        // Vérifier si la commande existe
        if (!$commande) {
            throw $this->createNotFoundException('La commande avec la référence ' . $refCommande . ' n\'a pas été trouvée.');
        }
        
        // Parcourir les produits associés à cette commande
        $produitsCommande = $entityManager->getRepository(ProduitCommande::class)->findBy(['refCommande' => $commande->getRefCommande()]);
        
        // Afficher les produits en utilisant leur référence (ref)
        $produits = [];
        foreach ($produitsCommande as $produitCommande) {
            $refProduit = $produitCommande->getRef();
            // Sélectionner le produit par sa référence (ref)
            $produit = $entityManager->getRepository(Produit::class)->findOneBy(['ref' => $refProduit]);
            if ($produit) {
                $produits[] = $produit;
            }
        }
        
        return $this->render('livreur/detailComLiv.html.twig', [
            'commande' => $commande,
            'produits' => $produits,
        ]);
    }



    #[Route('/modifierStatutCommande/{refCommande}/{nouveauStatut}', name: 'modifier_statut_commande')]
    public function modifierStatutCommande(Request $request, $refCommande, $nouveauStatut): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        // Sélectionner la commande par sa référence (refCommande)
        $commande = $entityManager->getRepository(Commande::class)->findOneBy(['refCommande' => $refCommande]);
        
        // Vérifier si la commande existe
        if (!$commande) {
            throw $this->createNotFoundException('La commande avec la référence ' . $refCommande . ' n\'a pas été trouvée.');
        }
        
        // Mettre à jour le statut de la commande
        $commande->setStatus($nouveauStatut);
        
        // Enregistrer les modifications dans la base de données
        $entityManager->flush();
        
        // Rediriger vers la page de détails de la commande
        return $this->redirectToRoute('commandeDetL', ['refCommande' => $refCommande]);
    }

    #[Route('/generateL/pdf/{refCommande}', name: 'generate_pdf_livreur')]
    public function generatePdfL($refCommande): Response
    {
        // Récupérer les données de la commande
        $entityManager = $this->getDoctrine()->getManager();
        $commande = $entityManager->getRepository(Commande::class)->findOneBy(['refCommande' => $refCommande]);
    
        // Afficher les produits en utilisant leur référence (ref)
        $produitsCommande = $entityManager->getRepository(ProduitCommande::class)->findBy(['refCommande' => $commande->getRefCommande()]);
        
        $produits = [];
        foreach ($produitsCommande as $produitCommande) {
            $refProduit = $produitCommande->getRef();
            // Sélectionner le produit par sa référence (ref)
            $produit = $entityManager->getRepository(Produit::class)->findOneBy(['ref' => $refProduit]);
            if ($produit) {
                $produits[] = $produit;
            }
        }
    
        // Créer un nouveau PDF
        $pdf = new TCPDF();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        
        // Générer le contenu du PDF à partir du template Twig
        $html = $this->renderView('livreur/pdf_template_livreur.html.twig', [
            'commande' => $commande,
            'produits' => $produits,
        ]);
        
        // Vider le tampon de sortie
        ob_clean();
        
        // Ajouter le contenu au PDF
        $pdf->writeHTML($html);
        
        // Télécharger le PDF
        $fileName = 'livraison_' . $commande->getRefCommande() . '.pdf';
        $pdf->Output($fileName, 'D');    }






}
