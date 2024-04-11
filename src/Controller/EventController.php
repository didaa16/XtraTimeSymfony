<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\SponsoRepository; 
use App\Repository\UserRepository;
use App\Repository\TerrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile; // Import UploadedFile
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Participation;


use Doctrine\ORM\EntityManagerInterface;


class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }


    //afficher 
    #[Route('/Affiche_event', name: 'event_Affiche')]
    public function Affiche(EventRepository $repository): Response
    {
        $events = $repository->findAll(); // Fetch all events
        return $this->render('event/Afficheevent.html.twig', ['e' => $events]);
    }


     //ajouter 
     #[Route('/Ajouter_event', name: 'app_Add')]
     public function Add(Request $request, SponsoRepository $sponsoRepository, TerrainRepository $terrainRepository, UserRepository $userRepository): Response
     {
         $event = new Event();
     
         // Fetch sponsor choices from the repository
         $sponsoChoices = $sponsoRepository->findAll();
         
         // Fetch terrain choices from the repository
         $terrainChoices = $terrainRepository->findAll();
         
         // Fetch user choices from the repository
         $userChoices = $userRepository->findAll();
         
         // Create the form with the sponsor, terrain, and user choices options
         $form = $this->createForm(EventType::class, $event, [
             'sponso_choices' => $sponsoChoices,
             'terrain_choices' => $terrainChoices,
             'user_choices' => $userChoices,
         ]);
         
         // Handle form submission
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) {
             // Handle image upload
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
         $event->setImage($fileName);
     }
             
            
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($event);
             $entityManager->flush();
             
             return $this->redirectToRoute('event_Affiche');
         }
         
         return $this->render('event/Addevent.html.twig', [
             'f' => $form->createView(),
         ]);
       
     }



     //supprimer
#[Route('/event_delete/{idevent}', name: 'event_delete')]
public function event_delete($idevent, EventRepository $repository)
{
    $event = $repository->find($idevent);

    

        if (!$event) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

    $em = $this->getDoctrine()->getManager();
    $em->remove($event);
    $em->flush();

    return $this->redirectToRoute('event_Affiche');
}


// Modifier
#[Route('/event_edit/{idevent}', name: 'event_edit')]
// Modify the edit action in your controller
public function edit(EventRepository $repository, $idevent, Request $request): Response
{
    $event = $repository->find($idevent);

    // Check if the event exists
    if (!$event) {
        throw new NotFoundHttpException('Event not found');
    }
    
    // Get the existing image file name if event is found
    $previousImage = $event->getImage();
    
    // Create the edit form with the retrieved event and the previous image
    $form = $this->createForm(EventType::class, $event);
    
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
            $event->setImage($fileName);
    
            // Remove the previous image file if it exists
            if ($previousImage) {
                unlink($this->getParameter('image_directory') . '/' . $previousImage);
            }
        } else {
            // If no new image file has been uploaded, keep the previous image value
            $event->setImage($previousImage);
        }
    
        // Save the changes to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
    
        return $this->redirectToRoute("event_Affiche");
    }
    
    return $this->render('event/editevent.html.twig', [
        'f' => $form->createView(),
        'event' => $event,
        'previousImage' => $previousImage, 
    ]);
}

}
