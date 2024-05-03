<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\File\File; // Importez la classe File
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\SponsoRepository; 
use App\Repository\UserRepository;
use App\Repository\TerrainRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile; // Import UploadedFile
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Participation;
use SymfonyCasts\Bundle\VerifyEmail\SymfonyCastsVerifyEmailBundle;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Dompdf\Dompdf;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Endroid\QrCode\Factory\QrCodeFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


use Doctrine\ORM\Tools\Pagination\Paginator;



class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }


   /* //afficher 
    #[Route('/Affiche_event', name: 'event_Affiche')]
    public function Affiche(EventRepository $repository): Response
    {
        $events = $repository->findAll(); // Fetch all events
        return $this->render('event/Afficheevent.html.twig', ['e' => $events]);
    }*/
//pagination+aff
#[Route('/Affiche_event', name: 'event_Affiche')]
public function Affiche(EventRepository $repository, Request $request): Response
{
    $searchQuery = $request->query->get('q');

    // Utilise QueryBuilder pour construire la requête
    $queryBuilder = $repository->createQueryBuilder('e');

    // Détermine le numéro de page à afficher à partir de la requête (défaut : page 1)
    $page = $request->query->getInt('page', 1);

    // Détermine le nombre d'éléments par page
    $perPage = 5;

    // Construit la requête avec orderBy si nécessaire
    $queryBuilder->orderBy('e.idevent', 'DESC'); // Exemple de tri par ID

    // Crée l'objet Paginator avec la requête construite
    $paginator = new Paginator($queryBuilder);

    // Définit l'offset et le nombre d'éléments max pour la pagination
    $paginator
        ->getQuery()
        ->setFirstResult(($page - 1) * $perPage)
        ->setMaxResults($perPage);

    // Récupère les résultats paginés
    $paginatedEvents = $paginator->getIterator();

    // Rend la vue avec les résultats paginés et les informations de pagination
    return $this->render('event/Afficheevent.html.twig', [
        'e' => $paginatedEvents,
        'currentPage' => $page,
        'searchQuery' => $searchQuery, // Pass the searchQuery to the template

    ]);
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



#[Route('/Affiche_front', name: 'event_Affiche_front')]
public function Affiche_front(EventRepository $repository): Response
{
    $events = $repository->findAll(); // Fetch all events
    return $this->render('event/AfficheeventFront.html.twig', ['e' => $events]); // Pass 'events' as variable
}


#[Route('/Back_details/{idevent}', name: 'event_back_details')]

public function Bdetails($idevent, EventRepository $eventRepository): Response
{
    // Récupérer les détails de l'événement en fonction de l'ID
    $event = $eventRepository->find($idevent);

    // Vérifier si l'événement existe
    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }

    // Rendre la vue des détails de l'événement avec les données de l'événement
    return $this->render('event/Backdetails.html.twig', [
        'event' => $event,
    ]);
}



#[Route('/Front_details/{idevent}', name: 'event_front_details')]

public function details($idevent, EventRepository $eventRepository): Response
{
    // Récupérer les détails de l'événement en fonction de l'ID
    $event = $eventRepository->find($idevent);

    // Vérifier si l'événement existe
    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }

    // Rendre la vue des détails de l'événement avec les données de l'événement
    return $this->render('event/details.html.twig', [
        'event' => $event,
    ]);
}
//paticipate
#[Route('/participate/{eventId}', name: 'app_participate')]
public function participate(
    MailerInterface $mailer,
    Request $request,
    EventRepository $eventRepository,
    UserRepository $utilisateursRepository,
    $eventId
): Response {
    // Récupérer l'événement à partir de son ID
    $event = $eventRepository->find($eventId);

    // Vérifier si l'événement existe
    if (!$event) {
        throw $this->createNotFoundException('L\'événement n\'existe pas');
    }

    // Récupérer l'utilisateur
    $user = $utilisateursRepository->findOneBy(['pseudo' => 'habib']);

    // Créer une nouvelle instance de Participation
    $participation = new Participation();
    $participation->setIduser($user);
    $participation->setIdevent($event);

    // Enregistrer la participation dans la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($participation);
    $entityManager->flush();

    // Préparer et envoyer l'e-mail de confirmation avec l'image de l'événement en pièce jointe
    $email = (new Email())
        ->from('lynda.mtiri@esprit.tn') // Adresse e-mail de l'expéditeur
        ->to('lynda.mtiri@esprit.tn') // Adresse e-mail du destinataire
        ->subject('Confirmation de participation') // Objet de l'e-mail
        ->text('Vous avez participé à l\'événement ' . $event->getTitre()); // Corps de l'e-mail

    // Chemin complet de l'image de l'événement
    $imagePath = $this->getParameter('image_directory') . '/' . $event->getImage();

    // Vérifier si le fichier image existe
    if (file_exists($imagePath)) {
        // Créer une instance de la classe File à partir du chemin de l'image
        $imageFile = new File($imagePath);

        // Attacher l'image à l'e-mail
        $email->attachFromPath($imageFile->getPathname(), $imageFile->getFilename());
    }

    /* Envoyer l'e-mail
    $mailer->send($email);
*/
    // Rediriger l'utilisateur vers la page de l'événement
    return $this->redirectToRoute('event_Affiche_front', ['id' => $eventId]);
}

/*
#[Route('/participate/{eventId}', name: 'app_participate')]
public function participate(
    Request $request,
    EventRepository $eventRepository,
    UserRepository $userRepository,
    MailerInterface $mailer, // Inject MailerInterface
    $eventId
): Response {
    // Récupérer l'événement à partir de son ID
    $event = $eventRepository->find($eventId);

    // Vérifier si l'événement existe
    if (!$event) {
        throw $this->createNotFoundException('L\'événement n\'existe pas');
    }
    
    // Fetch the user from UserRepository
    $user = $userRepository->findOneBy(['pseudo' => 'habib']);
    
    // Créer une nouvelle instance de Participation
    $participation = new Participation();

    // Set the user and event
    $participation->setIduser($user);
    $participation->setIdevent($event);

    // Enregistrer la participation dans la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($participation);
    $entityManager->flush();

    // Prepare and send confirmation email
    $email = (new Email())
        ->from('mohamed.bsila@esprit.tn') // Set sender email address
        ->to('lynda.mtiri@esprit.tn') // Set recipient email address
        ->subject('Confirmation de participation') // Set email subject
        ->text('Vous avez participé à l\'événement ' . $event->getTitre()); // Set email body
    
    // Send the email
    $mailer->send($email);

    // Rediriger l'utilisateur vers la page de l'événement
    return $this->redirectToRoute('event_Affiche_front', ['id' => $eventId]);
}
*/





#[Route('/stats', name: 'app_stats')]
public function stat(EventRepository $eventRepository): Response
{
    // Récupérer les événements avec leurs dates de début
    $events = $eventRepository->findAll();

    // Initialiser un tableau pour stocker le nombre d'événements par mois
    $eventsByMonth = [];

    // Parcourir tous les événements
    foreach ($events as $event) {
        // Récupérer le mois de la date de début de l'événement sous forme de nom complet
        $month = $event->getDatedebut()->format('F');
    
        // Incrémenter le compteur pour ce mois
        if (!isset($eventsByMonth[$month])) {
            $eventsByMonth[$month] = 1;
        } else {
            $eventsByMonth[$month]++;
        }
    }
    
    return $this->render('event/eventsByMonth.html.twig', [
        'eventsByMonth' => $eventsByMonth,
    ]);
}





//pdf
#[Route('/events/pdf', name: 'event_pdf')]
public function generatePdf(EventRepository $eventRepository): Response
{
    // Récupérer la liste des événements depuis le repository
    $events = $eventRepository->findAll();

    // Créer une instance de Dompdf
    $dompdf = new Dompdf();

    // Générer le HTML pour la liste des événements
    $html = $this->renderView('event/pdf.html.twig', ['events' => $events]);

    // Charger le HTML dans Dompdf
    $dompdf->loadHtml($html);

    // Rendre le PDF
    $dompdf->render();

    // Générer le nom du fichier PDF
    $pdfFileName = 'liste_des_evenements.pdf';

    // Envoyer le PDF en réponse avec l'en-tête Content-Disposition réglé sur "attachment"
    return new Response(
        $dompdf->output(),
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $pdfFileName . '"',
        ]
    );
}




   



  
//qrcode 


#[Route('/generate_qr_code/{idevent}', name: 'generate_qr_code')]
public function generateQrCode($idevent, EventRepository $eventRepository): Response
{
    // Récupérer l'événement à partir de son ID
    $event = $eventRepository->find($idevent);

    // Vérifier si l'événement existe
    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }

    // Générer le contenu du QR code avec les informations de l'événement
    $qrCodeContent = 'Informations sur l\'événement : ' . $event->getTitre() . ', Date : ' . $event->getDatedebut()->format('Y-m-d');

    // Créer le QR code en utilisant le bundle ou l'API choisi
    $qrCode = new QrCode($qrCodeContent);

    // Utiliser un écrivain (writer) pour obtenir le résultat du QR code sous forme d'objet ResultInterface
    $writer = new PngWriter();
    $qrCodeResult = $writer->write($qrCode);

    // Retourner le QR code dans une réponse HTTP
    return new QrCodeResponse($qrCodeResult);
}


  //Calendrier
  #[Route('/cc', name: 'app_calendar')]
public function calendar(EventRepository $eventRepository): Response
{
    // Récupérer les événements depuis le repository
    $events = $eventRepository->findAll();

    // Formater les données des événements pour le template Twig
    $formattedEvents = [];
    foreach ($events as $event) {
        $formattedEvents[] = [
            'id' => $event->getIdevent(),
            'title' => $event->getTitre(),
            'start' => $event->getDatedebut()->format('Y-m-d H:i:s'),
            'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
            // Ajouter d'autres propriétés d'événement si nécessaire
        ];
    }
    $data = json_encode($formattedEvents);
    
    return $this->render('event/calendar.html.twig', compact('data'));

   
   
}



//partc_stat
//stat 
#[Route('/nbr', name: 'nbr_participation')]
public function nbr(): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Fetch all participations
    $participations = $entityManager->getRepository(Participation::class)->findAll();

    // Initialize an array to hold event participation counts
    $eventParticipationCounts = [];

    // Count participations per event
    foreach ($participations as $participation) {
        $eventName = $participation->getIdevent()->getTitre();

        if (!isset($eventParticipationCounts[$eventName])) {
            $eventParticipationCounts[$eventName] = 0;
        }

        $eventParticipationCounts[$eventName]++;
    }

    return $this->render('event/eventsByMonth.html.twig', [
        'eventParticipationCounts' => $eventParticipationCounts, // Données pour le second graphique
    ]);
}

//recherche
#[Route('/search_event', name: 'event_search')]
public function search(EventRepository $repository, Request $request): Response
{
    $query = $request->query->get('q'); // Récupérer le terme de recherche depuis la requête GET

    // Utilise QueryBuilder pour construire la requête
    $queryBuilder = $repository->createQueryBuilder('e');

    // Filtrer les événements selon le terme de recherche
    if ($query) {
        $queryBuilder->where('e.titre LIKE :query')
            ->setParameter('query', '%' . $query . '%');
    }

    // Détermine le numéro de page à afficher à partir de la requête (défaut : page 1)
    $page = $request->query->getInt('page', 1);

    // Détermine le nombre d'éléments par page
    $perPage = 5;

    // Construit la requête avec orderBy si nécessaire
    $queryBuilder->orderBy('e.idevent', 'DESC');

    // Crée l'objet Paginator avec la requête construite
    $paginator = new Paginator($queryBuilder);

    // Définit l'offset et le nombre d'éléments max pour la pagination
    $paginator
        ->getQuery()
        ->setFirstResult(($page - 1) * $perPage)
        ->setMaxResults($perPage);

    // Récupère les résultats paginés
    $paginatedEvents = $paginator->getIterator();

    // Rend la vue avec les résultats paginés et les informations de pagination
    return $this->render('event/Afficheevent.html.twig', [
        'e' => $paginatedEvents,
        'currentPage' => $page,
        'searchQuery' => $query, // Passer le terme de recherche à la vue
    ]);
}


}
