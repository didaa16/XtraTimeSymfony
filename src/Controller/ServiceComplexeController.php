<?php

namespace App\Controller;

use App\Repository\ComplexeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ComplexeType;
use App\Entity\Complexe;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

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


    #[Route('/Complexe/add2', name: 'Complexe_add2' )]
public function add2(ManagerRegistry $doctrine , Request $request): Response
{
    $em = $doctrine->getManager();
    $complexe = new Complexe();
    $form = $this->createForm(ComplexeType::class, $complexe);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($complexe);
        $em->flush();
        return $this->redirectToRoute('Complexe'); // Redirection vers la page d'accueil ou une autre page
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
public function delete(ManagerRegistry $doctrine,ComplexeRepository $complexerepo, $ref)
{
    $em = $doctrine->getManager();
    $complexe = $complexerepo->find($ref);

    $em->remove($complexe);
    $em->flush();

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
}
