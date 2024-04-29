<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Repository\ComplexeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ComplexeType;
use App\Entity\Complexe;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse; // Importation de JsonResponse

class ServiceComplexeController extends AbstractController
{
    #[Route('/Complexe', name: 'Complexe' )]
    public  function About() :Response {
       return $this -> render('/Client/about.html.twig');
    }

    #[Route('/Complexe/read', name: 'Complexe_read' )]
    public function read(ComplexeRepository $complexerepo): Response
    {
        $complexes = $complexerepo->findAll();
        return $this->render('/service_complexe/read.html.twig',['complexes'=>$complexes]);
    }
    
    #[Route('/Complexe/read2', name: 'Complexe_read2' )]
    public function read2(ComplexeRepository $complexerepo): Response
    {
        $complexes = $complexerepo->findAll();
        return $this->render('/service_complexe/read2.html.twig',['complexes'=>$complexes]);
    }
    
    #[Route('/Complexe/add2', name: 'Complexe_add2')]
    public function add2(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $complexe = new Complexe();
        $form = $this->createForm(ComplexeType::class, $complexe);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload for 'image' field
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // Generate a unique name for the file
                $imageName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                // Move the file to the directory where images are stored
                $imageFile->move(
                    $this->getParameter('upload_directory'), // Specify your image directory here
                    $imageName
                );
                // Set the image property of your entity
                $complexe->setImage($imageName);
            }
        
            // Handle file upload for 'patente' field
            $patenteFile = $form->get('patente')->getData();
            if ($patenteFile) {
                // Generate a unique name for the file
                $patenteName = md5(uniqid()) . '.' . $patenteFile->guessExtension();
                // Move the file to the directory where images are stored
                $patenteFile->move(
                    $this->getParameter('upload_directory'), // Specify your image directory here
                    $patenteName
                );
                // Set the patente property of your entity
                $complexe->setPatente($patenteName);
            }
        
            $em->persist($complexe);
            $em->flush();
            return $this->redirectToRoute('Complexe_read'); // Redirection vers la page d'accueil ou une autre page
        }
        
        return $this->render('/service_complexe/add2.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/Complexe/edit/{ref}', name: 'edit_complexe')]
    public function edit(ManagerRegistry $doctrine, ComplexeRepository $complexerepo, Request $request, $ref)
    {
        $complexe = $complexerepo->find($ref);
        $em = $doctrine->getManager();
        $form = $this->createForm(ComplexeType::class, $complexe);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($complexe);
            $em->flush();
            return $this->redirectToRoute("Complexe_read");
        }

        return $this->renderForm("service_complexe/edit.html.twig", ["form" => $form]);
    }
    
    
    #[Route('/complexe/delete/{ref}', name: 'delete_complexe')]
    public function delete(ManagerRegistry $doctrine,ComplexeRepository $complexerepo, $ref, SessionInterface $session)
    {
        $em = $doctrine->getManager();
        $complexe = $complexerepo->find($ref);

        $em->remove($complexe);
        $em->flush();

        // Ajouter un message flash pour la notification
        $session->getFlashBag()->add('success', 'Le complexe a été supprimé avec succès.');

        return $this->redirectToRoute("Complexe_read");
    }
    
    
    #[Route('/Complexe/edit2/{ref}', name: 'edit_complexe2')]
    public function edit2(ManagerRegistry $doctrine, ComplexeRepository $complexerepo, Request $request, $ref)
    {
        $complexe = $complexerepo->find($ref);
        $em = $doctrine->getManager();
        $form = $this->createForm(ComplexeType::class, $complexe);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($complexe);
            $em->flush();
            return $this->redirectToRoute("Complexe_read2");
        }

        return $this->renderForm("service_complexe/edit2.html.twig", ["form" => $form]);
    }
    
    #[Route('/complexe/delete2/{ref}', name: 'delete_complexe2')]
    public function delete2(ManagerRegistry $doctrine,ComplexeRepository $complexerepo, $ref)
    {
        $em = $doctrine->getManager();
        $complexe = $complexerepo->find($ref);

        $em->remove($complexe);
        $em->flush();

        return $this->redirectToRoute("Complexe_read2");
    }
    #[Route('/generate-pdf', name: 'generate_pdf')]
    public function generatePdf(ComplexeRepository $complexerepo): Response
    {
        $complexes = $complexerepo->findAll();

        // Générer le HTML pour le contenu du PDF
        $html = "<h1>Liste des complexes</h1>";
        $html .= "<table>";
        $html .= "<thead><tr><th>Nom</th><th>Adresse</th><th>Téléphone</th><th>Patente</th><th>Image</th></tr></thead>";
        $html .= "<tbody>";
        foreach ($complexes as $complexe) {
            $html .= "<tr>";
            $html .= "<td>" . $complexe->getNom() . "</td>";
            $html .= "<td>" . $complexe->getAdresse() . "</td>";
            $html .= "<td>" . $complexe->getTel() . "</td>";
            $html .= "<td>" . $complexe->getPatente() . "</td>";
            $html .= "<td>" . $complexe->getImage() . "</td>";
           
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";

        // Générer le PDF
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Envoie le PDF en réponse
        $dompdf->stream("liste_complexes.pdf", [
            "Attachment" => true
        ]);

        return new Response();
    }
}
