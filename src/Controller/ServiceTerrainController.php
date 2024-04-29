<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Repository\TerrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TerrainType;
use App\Entity\Terrain;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
class ServiceTerrainController extends AbstractController
{
    #[Route('/Terrain', name: 'Terrain')]
    public function index(): Response
    {
        return $this->render('front.html.twig');
    }
    #[Route('/Terrain/read', name: 'Terrain_read' )]
    public function read(TerrainRepository $terrainrepo): Response
    {
        $terrains = $terrainrepo->findAll();
        return $this->render('/service_terrain/read.html.twig',['terrains'=>$terrains]);
    }
    #[Route('/Terrain/read2', name: 'Terrain_read2' )]
    public function read2(TerrainRepository $terrainrepo): Response
    {
        $terrains = $terrainrepo->findAll();
        return $this->render('/service_terrain/read2.html.twig',['terrains'=>$terrains]);
    }

    #[Route('/Terrain/add2', name: 'terrain_add2' )]
    public function add2(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $terrain = new Terrain();
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload for 'img' field
            $imgFile = $form->get('img')->getData();
            if ($imgFile) {
                // Generate a unique name for the file
                $imgName = md5(uniqid()) . '.' . $imgFile->guessExtension();
                // Move the file to the directory where images are stored
                $imgFile->move(
                    $this->getParameter('upload_directory'), // Specify your image directory here
                    $imgName
                );
                // Set the img property of your entity
                $terrain->setImg($imgName);
            }
    
            $em->persist($terrain);
            $em->flush();
            return $this->redirectToRoute('Terrain_read'); // Redirection vers la page d'accueil ou une autre page
        }
    
        return $this->render('/service_terrain/add2.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
#[Route('/Terrain/edit/{id}', name: 'edit_terrain')]
public function edit(ManagerRegistry $doctrine, TerrainRepository $terrainrepo, Request $request, $id)
{
    $terrain = $terrainrepo->find($id);
    $em = $doctrine->getManager();
    $form = $this->createForm(TerrainType::class, $terrain);

    $form->handleRequest($request);
    if ($form->isSubmitted()) {
        $em->persist($terrain);
        $em->flush();
        return $this->redirectToRoute("Terrain_read");
    }

    return $this->renderForm("service_terrain/edit.html.twig", ["form" => $form]);
}
#[Route('/terrain/delete/{id}', name: 'delete_terrain')]
public function delete(ManagerRegistry $doctrine,TerrainRepository $terrainrepo, $id)
{
    $em = $doctrine->getManager();
    $terrain = $terrainrepo->find($id);

    $em->remove($terrain);
    $em->flush();

    return $this->redirectToRoute("Terrain_read");
}
#[Route('/Terrain/edit2/{id}', name: 'edit_terrain2')]
public function edit2(ManagerRegistry $doctrine, TerrainRepository $terrainrepo, Request $request, $id)
{
    $terrain = $terrainrepo->find($id);
    $em = $doctrine->getManager();
    $form = $this->createForm(TerrainType::class, $terrain);

    $form->handleRequest($request);
    if ($form->isSubmitted()) {
        $em->persist($terrain);
        $em->flush();
        return $this->redirectToRoute("Terrain_read2");
    }

    return $this->renderForm("service_terrain/edit2.html.twig", ["form" => $form]);
}
#[Route('/terrain/delete2/{id}', name: 'delete_terrain2')]
public function delete2(ManagerRegistry $doctrine,TerrainRepository $terrainrepo, $id)
{
    $em = $doctrine->getManager();
    $terrain = $terrainrepo->find($id);

    $em->remove($terrain);
    $em->flush();

    return $this->redirectToRoute("Terrain_read2");
}

#[Route('/terrain/type/stats', name: 'terrain_statistics')]
public function terrainTypeStats(TerrainRepository $terrainRepository): Response
{
    $terrains = $terrainRepository->findAll();

    $terrainTypes = [];
    foreach ($terrains as $terrain) {
        $type = $terrain->getType();
        if (!isset($terrainTypes[$type])) {
            $terrainTypes[$type] = 0;
        }
        $terrainTypes[$type]++;
    }

    $dataForChart = [
        'labels' => array_keys($terrainTypes),
        'data' => array_values($terrainTypes)
    ];

    return $this->render('service_terrain/terrain_statistics.html.twig', [
        'dataForChart' => $dataForChart,
    ]);
}
}
