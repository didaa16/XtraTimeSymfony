<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\editBackType;
use App\Form\EditFrontType;
use App\Form\UtilisateursType;
use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


#[Route('/utilisateurs')]
class UtilisateursController extends AbstractController
{

    private $passwordEncoder;

    // Constructor injection of UserPasswordEncoderInterface
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/', name: 'app_utilisateurs_index', methods: ['GET'])]
    public function index(UtilisateursRepository $utilisateursRepository, Request $request): Response
    {
        $search = $request->query->get('q');
        $utilisateurs = $utilisateursRepository->findBySearch($search);

        return $this->render('utilisateurs/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }


    #[Route('/new', name: 'app_utilisateurs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateurs();
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password before persisting the Utilisateur entity
            $password = $form->get('password')->getData();
            $encodedPassword = $this->passwordEncoder->encodePassword($utilisateur, $password);
            $utilisateur->setPassword($encodedPassword);

            $utilisateur->setRoles(['Livreur']);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateurs_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('utilisateurs/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateurs_show', methods: ['GET'])]
    public function show(Utilisateurs $utilisateur): Response
    {
        return $this->render('utilisateurs/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
    #[Route('/front/{id}', name: 'app_utilisateurs_display', methods: ['GET'])]
    public function display(Utilisateurs $utilisateur): Response
    {
        return $this->render('utilisateurs/display.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateurs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateurs $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(editBackType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateurs/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateurs_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateurs $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateurs_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/utilisateur/modifier/{id}', name: 'modifier_utilisateur')]
    public function modifier($id , UtilisateursRepository $userRepo, ManagerRegistry $doctrine, Request $request)
    {
        $user = $userRepo->find($id);
        $em = $doctrine->getManager();
        $frm = $this->createForm(EditFrontType::class, $user);
        $frm->handleRequest($request);

        if ($frm->isSubmitted() && $frm->isValid()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("app_front");
        }

        // Si le formulaire n'est pas valide, il sera automatiquement réaffiché avec les erreurs
        return $this->render('home/frontIncludes/modifier.html.twig', [
            "form" => $frm->createView(),
        ]);
    }

    #[Route('/statistiques/pie-chart', name: 'statistiques_pie_chart')]
    public function pieChart(UtilisateursRepository $userRepository): Response
    {
        // 1. Extraire les données de la base de données
        $usersByRole = $userRepository->countUsersByRole();

        // 2. Préparer les données pour le graphique
        $labels = [];
        $data = [];
        $totalUsers = 0;

        // Calculer le nombre total d'utilisateurs
        foreach ($usersByRole as $roleData) {
            $totalUsers += $roleData['count'];
        }

        foreach ($usersByRole as $roleData) {
            $role = $roleData['roles'];
            $count = $roleData['count'];
            $labels[] = $role;
            $percentage = ($count / $totalUsers) * 100;
            $data[] = round($percentage, 2);
        }


        // 3. Afficher le graphique
        return $this->render('home/back.html.twig', [
            'labels' => json_encode($labels),
            'data' => json_encode($data),
        ]);
    }




}
