<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request; // Import de la classe Request
use TCPDF;
use Dompdf\Dompdf;
use Fpdf\Fpdf;


class ProduitCommandeController extends AbstractController
{
    #[Route('/produit/commande', name: 'app_produit_commande')]
    public function index(): Response
    {
        return $this->render('produit_commande/index.html.twig', [
            'controller_name' => 'ProduitCommandeController',
        ]);
    }


    #[Route('/commandeDet/{refCommande}', name: 'commandeDet')]
    public function commandeDet($refCommande): Response
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
        
        return $this->render('produit_commande/detailCom.html.twig', [
            'commande' => $commande,
            'produits' => $produits,
        ]);
    }


    #[Route('/generate/pdf/{refCommande}', name: 'generate_pdf')]
public function generatePdf($refCommande): Response
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
    $html = $this->renderView('produit_commande/pdf_template.html.twig', [
        'commande' => $commande,
        'produits' => $produits,
    ]);
    
    // Vider le tampon de sortie
    ob_clean();
    
    // Ajouter le contenu au PDF
    $pdf->writeHTML($html);
    
    // Télécharger le PDF
    $fileName = 'Commande_' . $commande->getRefCommande() . '.pdf';
$pdf->Output($fileName, 'D');
}





}
