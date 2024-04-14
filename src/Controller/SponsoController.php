<?php

namespace App\Controller;

use App\Entity\Sponso;
use App\Form\SponsoType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SponsoRepository; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile; // Import UploadedFile
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SponsoController extends AbstractController
{
    #[Route('/sponso', name: 'app_sponso')]
    public function index(): Response
    {
        return $this->render('sponso/index.html.twig', [
            'controller_name' => 'SponsoController',
        ]);
    }


     //afficher 
     #[Route('/Affiche_sponso', name: 'sponso_Affiche')]
     public function Affiche(SponsoRepository $repository): Response
     {
         $sponsos = $repository->findAll(); // Fetch all events
         return $this->render('sponso/Affichesponso.html.twig', ['s' => $sponsos]);
     }



     //supprimer
#[Route('/sponso_delete/{idsponso}', name: 'sponso_delete')]
public function event_delete($idsponso, SponsoRepository $repository)
{
    $sponso = $repository->find($idsponso);

    

        if (!$sponso) {
            throw $this->createNotFoundException('Sponso non trouvé');
        }

    $em = $this->getDoctrine()->getManager();
    $em->remove($sponso);
    $em->flush();

    return $this->redirectToRoute('sponso_Affiche');
}


//ajouter 
#[Route('/Ajouter_sponso', name: 'app_Addsponso')]

public function  Add (Request  $request)
{
    $sponso=new Sponso();
    $form =$this->CreateForm(SponsoType::class,$sponso);
    $form->handleRequest($request);
    

    if ($form->isSubmitted() && $form->isValid())
    {       // Handle image upload
        $imageFile = $form->get('image')->getData();
if ($imageFile) {
    // Get the original file name
    $fileName = $imageFile->getClientOriginalName();
            
    // Move the file to the directory where images are stored
    $imageFile->move(
        $this->getParameter('image_directory'),
        $fileName
    );
            
    // Update the 'image' property of the pack entity with the file name
    $sponso->setImage($fileName);
}
      
        $em=$this->getDoctrine()->getManager();
        $em->persist($sponso);
        $em->flush();
        return $this->redirectToRoute('sponso_Affiche');
    }
    return $this->render('sponso/Addsponso.html.twig',['f'=>$form->createView()]);

}   

// Modifier
#[Route('/sponso_edit/{idsponso}', name: 'sponso_edit')]
// Modify the edit action in your controller
public function edit(SponsoRepository $repository, $idsponso, Request $request): Response
{
    $sponso = $repository->find($idsponso);

    // Check if the event exists
    if (!$sponso) {
        throw new NotFoundHttpException('Event not found');
    }
    
    // Get the existing image file name if event is found
    $previousImage = $sponso->getImage();
    
    // Create the edit form with the retrieved event and the previous image
    $form = $this->createForm(SponsoType::class, $sponso);
    
    // Handle form submission
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Handle image upload manually
        $imageFile = $form->get('image')->getData();
    
        if ($imageFile instanceof UploadedFile) {
            // Generate a unique name for the file before saving it
          
            $fileName = $imageFile->getClientOriginalName();

    
            // Move the file to the directory where images are stored
            $imageFile->move(
                $this->getParameter('image_directory'),
                $fileName
            );
    
            // Update the 'image' property of the event entity with the new file name
            $sponso->setImage($fileName);
    
            // Remove the previous image file if it exists
            if ($previousImage) {
                unlink($this->getParameter('image_directory') . '/' . $previousImage);
            }
        } else {
            // If no new image file has been uploaded, keep the previous image value
            $sponso->setImage($previousImage);
        }
    
        // Save the changes to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
    
        return $this->redirectToRoute("sponso_Affiche");
    }
    
    return $this->render('sponso/editsponso.html.twig', [
        'f' => $form->createView(),
        'sponso' => $sponso,
        'previousImage' => $previousImage, 
    ]);
}



#[Route('/Sponso_front', name: 'sponso_Affiche_front')]
public function Affiche_front(SponsoRepository $repository): Response
{
    $sponsors = $repository->findAll(); // Fetch all events
    return $this->render('sponso/AfficheSponsoFront.html.twig', ['s' => $sponsors]); // Pass 'events' as variable
}


#[Route('/FrontSponso_details/{idsponso}', name: 'sponso_front_details')]
public function details($idsponso, SponsoRepository $sponsoRepository): Response
{
    // Récupérer les détails de l'événement en fonction de l'ID
    $sponso = $sponsoRepository->find($idsponso);

    // Vérifier si l'événement existe
    if (!$sponso) {
        throw $this->createNotFoundException('Sponso not found');
    }

    // Rendre la vue des détails de l'événement avec les données de l'événement
    return $this->render('sponso/Sponsodetails.html.twig', [
        'sponso' => $sponso, // Utiliser le nom de variable correct
    ]);
}


#[Route('/BackSponso_details/{idsponso}', name: 'sponso_back_details')]
public function Bdetails($idsponso, SponsoRepository $sponsoRepository): Response
{
    // Récupérer les détails de l'événement en fonction de l'ID
    $sponso = $sponsoRepository->find($idsponso);

    // Vérifier si l'événement existe
    if (!$sponso) {
        throw $this->createNotFoundException('Sponso not found');
    }

    // Rendre la vue des détails de l'événement avec les données de l'événement
    return $this->render('sponso/SponsodetailsBack.html.twig', [
        'sponso' => $sponso, // Utiliser le nom de variable correct
    ]);
}

}
