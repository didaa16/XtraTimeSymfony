<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Form\PackType;
use App\Repository\PackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/pack')]
class PackController extends AbstractController
{


    #[Route('/new', name: 'app_pack_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pack = new Pack();
        $form = $this->createForm(PackType::class, $pack);
    
    
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
                
                // Update the 'image' property of the pack entity with the file name
                $pack->setImage($fileName);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pack);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_pack_show');
        }
    
        return $this->render('pack/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/show', name: 'app_pack_show', methods: ['GET'])]
    public function show(PackRepository $packrepo): Response
    {
        $packs = $packrepo->findAll();
        
        return $this->render('/pack/tables-listjsPack.html.twig',['packs'=>$packs]);
    }

    #[Route('/{idp}/edit', name: 'app_pack_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $idp, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le pack à éditer depuis la base de données
        $pack = $this->getDoctrine()->getRepository(Pack::class)->find($idp);
    
        if (!$pack) {
            throw $this->createNotFoundException('Pack non trouvé');
        }
    
        // Sauvegarder l'ancienne valeur de l'image
        $ancienneImage = $pack->getImage();
    
        $form = $this->createForm(PackType::class, $pack);
    
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
    
                // Mettre à jour la propriété 'image' de l'entité pack avec le nom du fichier
                $pack->setImage($nomFichier);
            } else {
                // Si aucun nouveau fichier image n'a été téléchargé, conserver l'ancienne valeur de l'image
                $pack->setImage($ancienneImage);
            }
    
            // Mettre à jour le pack avec les nouvelles données
            $entityManager->flush();
    
            // Redirection vers une autre page après la modification réussie
            return $this->redirectToRoute('app_pack_show');
        }
    
        return $this->render('pack/editerPack.html.twig', [
            'form' => $form->createView(),
            'pack' => $pack,
            'ancienneImage' => $ancienneImage, // Passer l'ancienne image au formulaire Twig
        ]);
    }

   

    #[Route('/{idp}', name: 'app_pack_delete', methods: ['POST'])]
    public function delete($idp, PackRepository $repository, EntityManagerInterface $entityManager): Response
{
    $pack = $repository->find($idp);
    
        if (!$pack) {
            throw $this->createNotFoundException('Pack non trouvé');
        }
    
        // Utilisez l'EntityManager (via $entityManager) pour supprimer l'entité
        $entityManager->remove($pack);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_pack_show');
}




#[Route('/showC', name: 'app_packC_show')]
public function showC(PackRepository $packrepo): Response
{
    $packs = $packrepo->findAll();
    
    return $this->render('pack/package-v2.html.twig',['packs'=>$packs]);
    
}


#[Route('/MoreDetailsPack/{idp}', name: 'MoreDetailsPack')]
public function MoreDetailsPack($idp): Response
{
    // Récupérer le pack depuis la base de données en fonction de l'identifiant
    $pack = $this->getDoctrine()->getRepository(Pack::class)->find($idp);

    if (!$pack) {
        throw $this->createNotFoundException('Pack non trouvé');
    }

    // Rendre la vue avec les détails du produit
    return $this->render('pack/single-package.html.twig', [
        'pack' => $pack,
    ]);
}

#[Route('/export-excel', name: 'app_pack_export_excel')]
public function exportToExcel(PackRepository $packRepo): StreamedResponse
{
    // Récupérer tous les packs depuis le repository
    $packs = $packRepo->findAll();

    // Créer un nouvel objet Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Entête du fichier Excel
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nom');
    $sheet->setCellValue('C1', 'Description');
    $sheet->setCellValue('D1', 'Réduction');
    $sheet->setCellValue('E1', 'Durée');
    $sheet->setCellValue('F1', 'Image');

    // Remplir les données des packs dans le fichier Excel
    $row = 2;
    foreach ($packs as $pack) {
        $sheet->setCellValue('A'.$row, $pack->getIdp());
        $sheet->setCellValue('B'.$row, $pack->getNom());
        $sheet->setCellValue('C'.$row, $pack->getDescription());
        $sheet->setCellValue('D'.$row, $pack->getReduction());
        $sheet->setCellValue('E'.$row, $pack->getDuree());
        $sheet->setCellValue('F'.$row, $pack->getImage());
        $row++;
    }

    // Créer un objet Writer pour écrire le contenu du fichier Excel
    $writer = new Xlsx($spreadsheet);

    // Créer une réponse HTTP avec le contenu du fichier Excel
    $response = new StreamedResponse(function () use ($writer) {
        // Écrire le contenu du fichier Excel dans la réponse HTTP
        $writer->save('php://output');
    });

    // Ajouter les en-têtes appropriés pour spécifier que le contenu est un fichier Excel
    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Content-Disposition', 'attachment;filename="liste_packs.xlsx"');
    $response->headers->set('Cache-Control', 'max-age=0');

    return $response;
}

#[Route('/search', name: 'pack_search_ajax', methods: ['GET'])]
public function  search(Request $request, PackRepository $packRepository)
    {
        $searchText = $request->query->get('searchText');

        if ($searchText) {
            $packs = $packRepository->findBySearchText($searchText);
        } else {
            $packs = [];
        }

        return $this->json($packs);
    }

}